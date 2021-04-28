<?php

namespace App\Http\Livewire;

use Storage;
use Exception;
use Livewire\Component;
use App\Models\CmsRecording;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CmsRecordingCard extends Component
{
    public CmsRecording $recording;
    public $editing = false;
    public $newRecordingName;
    public $newRecordingNameError = '';
    public $newRecordingNameHasErrors = false;

    /**
     * Set the current filename being edited
     * and make that the default for the new filename
     */
    public function loadRecordingNames()
    {
        $this->editing = true;
        $this->newRecordingNameHasErrors = false;
        $this->newRecordingName = $this->recording->filename;
    }

    /**
     * Update the UI and NFS storage with the new filename
     */
    public function saveNewRecordingName()
    {

        try {

            Storage::disk('recordings')->move(
                "{$this->recording->cmsCoSpace->space_id}/{$this->recording->filename}",
                "{$this->recording->cmsCoSpace->space_id}/{$this->newRecordingName}"
            );

            try {
                $this->recording->update([
                    'filename' => $this->newRecordingName
                ]);
                $this->editing = false;
                $this->newRecordingName = '';
            } catch(Exception $e) {
                info('error', [$e->getMessage()]);
                $this->newRecordingName = '';
                $this->newRecordingNameHasErrors = true;
                $this->newRecordingNameError = 'There was an error updating the filename';
                $this->editing = false;
            }

        } catch(Exception $e) {
            $this->editing = false;
            if(!substr($e->getMessage(), 0, 27 ) === "File already exists at path") {
                info('error', [$e->getMessage()]);
                $this->newRecordingName = '';
                $this->newRecordingNameHasErrors = true;
                $this->newRecordingNameError = 'There was an error updating the filename';
            }
        }
    }

    /**
     * Cancel the filename change operation
     */
    public function cancelNewRecordingName()
    {
        $this->newRecordingNameHasErrors = false;
        $this->newRecordingName = '';
    }

    /**
     * Perform validation on the new filename
     */
    public function updatedNewRecordingName()
    {
        $fileExtension = pathinfo($this->newRecordingName, PATHINFO_EXTENSION);
        if((empty($this->newRecordingName))) {
            $this->newRecordingNameHasErrors = true;
            $this->newRecordingNameError = 'The filename cannot be empty';
        } elseif(!in_array($fileExtension, ['mp4'])) {
            $this->newRecordingNameHasErrors = true;
            $this->newRecordingNameError = 'The filename must end in .mp4';
        } else {
            $this->newRecordingNameHasErrors = false;
        }
    }

    /**
     * Download the recording
     *
     * @param $recording
     * @return StreamedResponse
     */
    public function downloadRecording($recording)
    {
        // TODO: Configure proper PHP memory limits!
        $downloadUrl = "{$this->recording->cmsCoSpace->space_id}/{$recording}";
        return \Storage::disk('recordings')->download($downloadUrl);
    }

    /**
     * Manage Recording share settings
     *
     * @param $bool
     */
    public function toggleSharing($bool)
    {
        $this->recording->update([
            'shared' => $bool
        ]);
    }

    public function render()
    {
        return view('livewire.cms-recording-card');
    }
}
