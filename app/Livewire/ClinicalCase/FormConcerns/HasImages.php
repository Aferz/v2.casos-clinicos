<?php

namespace App\Livewire\ClinicalCase\FormConcerns;

use App\Models\ClinicalCaseMedia;
use App\Services\Features\ImagesFeature;
use Illuminate\Http\File;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

trait HasImages
{
    use WithFileUploads;

    public $imageModel;
    public string $randomInputId;
    public array $images = [];
    public int $imagesMinimum;
    public int $imagesMaximum;
    public bool $imagesAreEnabled;
    public string $imagesAccept;
    public string $imagesRules;
    public string $imagesDescriptionsRules;

    public function mountHasImages(
        ImagesFeature $imagesFeature
    ): void {
        $this->randomInputId = Str::random();

        $this->imagesAreEnabled = $imagesFeature->enabled();
        $this->imagesMinimum = $imagesFeature->min();
        $this->imagesMaximum = $imagesFeature->max();
        $this->imagesAccept = $imagesFeature->accept();
        $this->imagesRules = $imagesFeature->rules();
        $this->imagesDescriptionsRules = $imagesFeature->rulesDescriptions();

        $this->prepareImages();
    }

    public function updatedImageModel(): void
    {
        if ($this->imageModel) {
            $this->images = array_slice(array_merge(
                $this->images,
                collect($this->imageModel)
                    ->map(fn (TemporaryUploadedFile $image) => [
                        'src' => 'tmp/' . $image->getFilename(),
                        'description' => '',
                        'new' => true,
                    ])
                    ->all()
            ), 0, $this->imagesMaximum);
        }

        $this->imageModel = null;
    }

    public function removeImage(int $index): void
    {
        array_splice($this->images, $index, 1);
    }

    protected function createImages(): void
    {
        if (! $this->imagesAreEnabled) {
            return;
        }

        $this->clinicalCase->media()->createMany(
            $this->getImagesToCreate($this->getImagesAsFiles())
        );
    }

    protected function updateImages(): void
    {
        if (! $this->imagesAreEnabled) {
            return;
        }

        $images = $this->getImagesAsFiles();

        // If there are no images validated, we should just delete
        // all the existing ones.

        if (count($images) === 0) {
            foreach ($this->clinicalCase->images as $image) {
                $this->deleteImageModel($image);
            }

            return;
        }

        // First, we need to check which of the images are existing images.
        // Those images that has a matching record in the validated ones
        // will be updated.

        $toPreserveImageIds = [];

        foreach ($images as $index => $image) {
            if ($image['new'] === false) {
                $existingImage = $this->clinicalCase
                    ->images
                    ->firstWhere('path', $image['src']);

                $toPreserveImageIds[] = $existingImage->id;

                $this->updateImageModel($existingImage, $image);

                unset($images[$index]);
            }
        }

        // Then, we are going to remove the images that are currently
        // attached to our clinical case BUT they weren't update
        // by the step above.

        $toRemoveImages = $this->clinicalCase->images()
            ->whereNotIn('id', $toPreserveImageIds)
            ->get();

        foreach ($toRemoveImages as $image) {
            $this->deleteImageModel($image);
        }

        // Last, we'll create the remaining images that were
        // validated.

        $this->clinicalCase->media()->createMany(
            $this->getImagesToCreate($images)
        );
    }

    protected function prepareImages(): void
    {
        if (! $this->imagesAreEnabled) {
            return;
        }

        if ($clinicalCase = $this->clinicalCase()) {
            $this->images = $clinicalCase
                ->images
                ->map(fn (ClinicalCaseMedia $image) => [
                    'src' => $image->path,
                    'description' => $image->description,
                    'new' => false,
                ])
                ->all();
        }
    }

    protected function getImagesValidationData(): array
    {
        if (! $this->imagesAreEnabled) {
            return [];
        }

        return [
            'images' => $this->getImagesAsFiles(),
        ];
    }

    protected function getImagesValidationRules(): array
    {
        if (! $this->imagesAreEnabled) {
            return [];
        }

        $rules = [];

        if ($this->imagesMinimum > 0 && $this->sending) {
            foreach (range(0, $this->imagesMinimum - 1) as $index) {
                $rules["images.$index.file"] = 'required';
                $rules["images.$index.description"] = 'required';
            }
        }

        $rules['images.*.file'] = $this->imagesRules;
        $rules['images.*.description'] = $this->imagesDescriptionsRules;

        return $rules;
    }

    protected function getImagesValidationMessages(): array
    {
        if (! $this->imagesAreEnabled) {
            return [];
        }

        $messages = [];

        foreach (range(0, $this->imagesMinimum - 1) as $index) {
            $messages["images.$index.file.required"] = trans_choice(
                'At least :min image is required|At least :min images are required',
                $this->imagesMinimum,
                ['min' => $this->imagesMinimum]
            );
        }

        foreach (range(0, $this->imagesMaximum - 1) as $index) {
            $messages["images.$index.description.required"] = __('This description is required');
        }

        return $messages;
    }

    protected function getImagesToCreate(array $images): array
    {
        return collect($images)
            ->map(fn (array $image) => [
                'type' => 'image',
                'description' => $image['description'],
                'path' => $this->moveImage($image['file']),
            ])
            ->all();
    }

    protected function getImagesAsFiles(): array
    {
        return collect($this->images)
            ->map(fn (array $image) => array_merge($image, [
                'file' => new File(storage_path('app/public/' . $image['src'])),
            ]))
            ->all();
    }

    protected function moveImage(File $image): string
    {
        $path = 'images/clinical-cases';

        $image->move(storage_path('app/public/' . $path));

        return $path . '/' . $image->getFilename();
    }

    protected function updateImageModel(ClinicalCaseMedia $image, array $data): void
    {
        $image->description = $data['description'];

        $image->save();
    }

    protected function deleteImageModel(ClinicalCaseMedia $image): void
    {
        FileFacade::delete(storage_path('app/public/' . $image->path));

        $image->delete();
    }
}
