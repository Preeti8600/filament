<?php

namespace Filament\Resources\Pages;

use Filament\Forms\HasForm;
use Filament\Resources\Forms\Form;
use Illuminate\Support\Str;

class EditRecord extends Page
{
    use HasForm;

    public static $deleteButtonLabel = 'filament::resources/pages/edit-record.buttons.delete.label';

    public static $deleteModalCancelButtonLabel = 'filament::resources/pages/edit-record.modals.delete.buttons.cancel.label';

    public static $deleteModalConfirmButtonLabel = 'filament::resources/pages/edit-record.modals.delete.buttons.confirm.label';

    public static $deleteModalDescription = 'filament::resources/pages/edit-record.modals.delete.description';

    public static $deleteModalHeading = 'filament::resources/pages/edit-record.modals.delete.heading';

    public static $saveButtonLabel = 'filament::resources/pages/edit-record.buttons.save.label';

    public static $savedMessage = 'filament::resources/pages/edit-record.messages.saved';

    public static $view = 'filament::resources.pages.edit-record';

    public $record;

    public $indexRoute = 'index';

    public static function getBreadcrumbs()
    {
        return [
            static::getResource()::generateUrl() => (string) Str::title(static::getResource()::getPluralLabel()),
        ];
    }

    public function delete()
    {
        $this->record->delete();

        $this->redirect($this->getResource()::generateUrl($this->indexRoute));
    }

    public function getForm()
    {
        return static::getResource()::form(Form::make())
            ->context(static::class)
            ->model(static::getModel())
            ->record($this->record)
            ->submitMethod('save');
    }

    public function mount($record)
    {
        $this->record = static::getModel()::findOrFail($record);
    }

    public function save()
    {
        $this->validateTemporaryUploadedFiles();

        $this->storeTemporaryUploadedFiles();

        $this->validate();

        $this->record->save();

        $this->notify(__(static::$savedMessage));
    }
}
