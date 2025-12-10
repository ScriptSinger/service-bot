<?php

namespace App\BotMan\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use App\Models\DeviceType;
use App\Models\Brand;
use App\Models\DeviceModel;
use App\Models\TelegramUser;

class DeviceSelectionConversation extends Conversation
{
    protected ?DeviceType $type = null;
    protected ?Brand $brand = null;
    protected ?DeviceModel $model = null;

    public function run(): void
    {
        $this->resumeOrStart();
    }

    /**
     * Определяем шаг по базе и продолжаем диалог
     */
    protected function resumeOrStart(): void
    {
        $user = TelegramUser::firstOrCreate(
            ['telegram_id' => $this->bot->getUser()->getId()]
        );

        $state = $user->state ?? 'waiting_type';
        $data = $user->state_data ?? [];

        switch ($state) {
            case 'waiting_type':
                $this->askDeviceType();
                break;
            case 'waiting_brand':
                $this->type = DeviceType::find($data['type_id'] ?? null);
                $this->askBrand();
                break;
            case 'waiting_model':
                $this->type = DeviceType::find($data['type_id'] ?? null);
                $this->brand = Brand::find($data['brand_id'] ?? null);
                $this->askModel();
                break;
            case 'waiting_manual':
                $this->type = DeviceType::find($data['type_id'] ?? null);
                $this->brand = Brand::find($data['brand_id'] ?? null);
                $this->model = DeviceModel::find($data['model_id'] ?? null);
                $this->askManual();
                break;
            default:
                $this->askDeviceType();
        }
    }

    public function askDeviceType(): void
    {
        $types = DeviceType::orderBy('name')->get();
        if ($types->isEmpty()) {
            $this->say('Нет доступных типов устройств.');
            return;
        }

        $buttons = $types->map(fn($type) => Button::create($type->name)->value($type->id))->toArray();
        $question = Question::create('Выберите тип устройства:')->addButtons($buttons);

        $this->ask($question, function ($answer) {
            $typeId = $answer->getValue();
            $type = DeviceType::find($typeId);

            if (!$type) {
                $this->say('Неверный выбор, попробуйте снова.');
                $this->askDeviceType();
                return;
            }

            $this->type = $type;
            $this->brand = null;
            $this->model = null;

            // Сохраняем состояние в БД
            $user = TelegramUser::firstOrCreate(['telegram_id' => $this->bot->getUser()->getId()]);
            $user->state = 'waiting_brand';
            $user->state_data = ['type_id' => $type->id];
            $user->save();

            $this->askBrand();
        });
    }

    public function askBrand(): void
    {
        if (!$this->type) {
            $this->askDeviceType();
            return;
        }

        $brands = $this->type->brands()->orderBy('name')->get();
        if ($brands->isEmpty()) {
            $this->say('Нет брендов для выбранного типа.');
            $this->askDeviceType();
            return;
        }

        $buttons = $brands->map(fn($brand) => Button::create($brand->name)->value($brand->id))->toArray();
        $buttons[] = Button::create('⬅️ Назад')->value('back');
        $question = Question::create('Выберите бренд:')->addButtons($buttons);

        $this->ask($question, function ($answer) {
            $value = $answer->getValue();

            if ($value === 'back') {
                $this->type = null;
                $this->brand = null;
                $this->model = null;

                $user = TelegramUser::firstOrCreate(['telegram_id' => $this->bot->getUser()->getId()]);
                $user->state = 'waiting_type';
                $user->state_data = null;
                $user->save();

                $this->askDeviceType();
                return;
            }

            $brand = Brand::find($value);
            if (!$brand) {
                $this->say('Неверный выбор, попробуйте снова.');
                $this->askBrand();
                return;
            }

            $this->brand = $brand;
            $this->model = null;

            $user = TelegramUser::firstOrCreate(['telegram_id' => $this->bot->getUser()->getId()]);
            $user->state = 'waiting_model';
            $user->state_data = [
                'type_id' => $this->type->id,
                'brand_id' => $brand->id,
            ];
            $user->save();

            $this->askModel();
        });
    }

    public function askModel(): void
    {
        if (!$this->type || !$this->brand) {
            $this->askDeviceType();
            return;
        }

        $models = $this->brand->deviceModels()
            ->where('device_type_id', $this->type->id)
            ->orderBy('name')->get();

        if ($models->isEmpty()) {
            $this->say('Нет моделей для выбранного бренда.');
            $this->askBrand();
            return;
        }

        $buttons = $models->map(fn($model) => Button::create($model->name)->value($model->id))->toArray();
        $buttons[] = Button::create('⬅️ Назад')->value('back');

        $question = Question::create('Выберите модель:')->addButtons($buttons);

        $this->ask($question, function ($answer) {
            $value = $answer->getValue();

            if ($value === 'back') {
                $this->model = null;

                $user = TelegramUser::firstOrCreate(['telegram_id' => $this->bot->getUser()->getId()]);
                $user->state = 'waiting_brand';
                $user->state_data = ['type_id' => $this->type->id];
                $user->save();

                $this->askBrand();
                return;
            }

            $model = DeviceModel::find($value);
            if (!$model) {
                $this->say('Неверный выбор, попробуйте снова.');
                $this->askModel();
                return;
            }

            $this->model = $model;

            $user = TelegramUser::firstOrCreate(['telegram_id' => $this->bot->getUser()->getId()]);
            $user->state = 'waiting_manual';
            $user->state_data = [
                'type_id' => $this->type->id,
                'brand_id' => $this->brand->id,
                'model_id' => $model->id,
            ];
            $user->save();

            $this->askManual();
        });
    }

    public function askManual(): void
    {
        if (!$this->type || !$this->brand || !$this->model) {
            $this->askDeviceType();
            return;
        }

        $manuals = $this->model->manuals()->with('files')->get();
        if ($manuals->isEmpty()) {
            $this->say('Для этой модели нет инструкций.');
            return;
        }

        $buttons = $manuals->map(fn($manual) => Button::create('Файл #' . $manual->id)->value($manual->id))->toArray();
        $buttons[] = Button::create('⬅️ Назад')->value('back');

        $question = Question::create('Выберите мануал:')->addButtons($buttons);

        $this->ask($question, function ($answer) use ($manuals) {
            $value = $answer->getValue();

            if ($value === 'back') {
                $this->model = null;

                $user = TelegramUser::firstOrCreate(['telegram_id' => $this->bot->getUser()->getId()]);
                $user->state = 'waiting_model';
                $user->state_data = [
                    'type_id' => $this->type->id,
                    'brand_id' => $this->brand->id,
                ];
                $user->save();

                $this->askModel();
                return;
            }

            $manual = $manuals->firstWhere('id', $value);
            if ($manual) {
                foreach ($manual->files as $file) {
                    $this->say("Файл: {$file->filename} ({$file->url})");
                }
            }

            $user = TelegramUser::firstOrCreate(['telegram_id' => $this->bot->getUser()->getId()]);
            $user->state = null;
            $user->state_data = null;
            $user->save();

            $this->say('Выбор завершён!');
        });
    }
}
