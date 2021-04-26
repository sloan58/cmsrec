<?php

namespace App\Http\Livewire;

use Livewire\Component;

class VideoCard extends Component
{
    public $space;
    public $newRecordingName;
    public $currentRecordingName;
    public $newRecordingNameError = '';
    public $newRecordingNameHasErrors = false;

    /**
     * Set the current filename being edited
     * and make that the default for the new filename
     *
     * @param $recordingName
     */
    public function loadRecordingNames($recordingName)
    {
        $this->newRecordingNameHasErrors = false;
        $this->currentRecordingName = $recordingName;
        $this->newRecordingName = $recordingName;
    }

    /**
     * Update the UI and NFS storage with the new filename
     */
    public function saveNewRecordingName()
    {
        foreach($this->space['recordings'] as $index => $recording) {
            if($recording['baseName'] === $this->currentRecordingName) {
                try {
                    \Storage::disk('recordings')->move(
                        "{$this->space['space_id']}/$this->currentRecordingName",
                        "{$this->space['space_id']}/$this->newRecordingName"
                    );
                    $this->space['recordings'][$index]['baseName'] = $this->newRecordingName;
                } catch(\Exception $e) {
                    if(!substr($e->getMessage(), 0, 27 ) === "File already exists at path") {
                        info('error', [$e->getMessage()]);
                        $this->newRecordingNameHasErrors = true;
                        $this->newRecordingNameError = 'There was an error updating the filename';
                    }
                }
                $this->currentRecordingName = '';
                $this->newRecordingName = '';

            }
        }
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

    public function render()
    {
        return view('livewire.video-card');
    }
}
