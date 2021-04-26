<?php

namespace App\Http\Livewire;

use Storage;
use Exception;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CmsRecordingCard extends Component
{
    public $recording;
    public $editing = false;
    public $newRecordingName;
    public $currentRecordingName;
    public $newRecordingNameError = '';
    public $newRecordingNameHasErrors = false;

    public $shareLinkCreated = false;

    /**
     * Set the current filename being edited
     * and make that the default for the new filename
     */
    public function loadRecordingNames()
    {
        $this->editing = true;
        $this->newRecordingNameHasErrors = false;
        $this->currentRecordingName = $this->recording['baseName'];
        $this->newRecordingName = $this->recording['baseName'];
    }

    /**
     * Update the UI and NFS storage with the new filename
     */
    public function saveNewRecordingName()
    {
        try {
            Storage::disk('recordings')->move(
                "{$this->recording['space_id']}/$this->currentRecordingName",
                "{$this->recording['space_id']}/$this->newRecordingName"
            );
            $this->recording['baseName'] = $this->newRecordingName;
            $this->recording['urlSafeFilename'] = urlencode(basename($this->recording['baseName']));
            $this->recording['sanitizedFilename'] = preg_replace("/[^A-Za-z0-9 ]/", '', explode('.', basename($this->recording['baseName']))[0]);
            $this->editing = false;
        } catch(Exception $e) {
            if(!substr($e->getMessage(), 0, 27 ) === "File already exists at path") {
                info('error', [$e->getMessage()]);
                $this->newRecordingNameHasErrors = true;
                $this->newRecordingNameError = 'There was an error updating the filename';
            }
            $this->editing = false;
        }
        $this->currentRecordingName = '';
        $this->newRecordingName = '';
    }

    /**
     * Cancel the filename change operation
     */
    public function cancelNewRecordingName()
    {
        $this->newRecordingNameHasErrors = false;
        $this->currentRecordingName = '';
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
        $downloadUrl = "{$this->recording['space_id']}/$recording";
        return \Storage::disk('recordings')->download($downloadUrl);
    }

    /**
     * Create a shareable link for a recording
     */
    public function createShareLink()
    {
        info('creating share link');
        $this->shareLinkCreated = true;
    }

    public function render()
    {
        return view('livewire.cms-recording-card');
    }
}
