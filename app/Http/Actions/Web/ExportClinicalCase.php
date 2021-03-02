<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\ClinicalCase;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ExportClinicalCase extends Action
{
    /**
     * @return Response|BinaryFileResponse
     */
    public function __invoke(ClinicalCase $clinicalCase)
    {
        $this->authorizeUserTo('exportList', ClinicalCase::class);

        $data = $this->validate();

        if ($data['as'] === 'zip') {
            return $this->downloadZip($clinicalCase);
        }

        return $this->downloadPdf($clinicalCase);
    }

    protected function rules(): array
    {
        return [
            'as' => 'required|in:pdf,zip',
        ];
    }

    protected function downloadPdf(ClinicalCase $clinicalCase): Response
    {
        $name = $this->fileName($clinicalCase) . '.pdf';

        return $this->createPdf($clinicalCase)->download($name);
    }

    protected function downloadZip(ClinicalCase $clinicalCase): BinaryFileResponse
    {
        $fileName = $this->fileName($clinicalCase);
        $zipName = sys_get_temp_dir() . "cc-{$clinicalCase->id}.zip";
        $pdfName = sys_get_temp_dir() . "cc-{$clinicalCase->id}.pdf";

        $this->createPdf($clinicalCase)->save($pdfName);

        $zip = new ZipArchive();
        $zip->open($zipName, ZipArchive::CREATE);
        $zip->addFile($pdfName, 'clinical-case.pdf');

        foreach ($clinicalCase->images as $index => $image) {
            $i = $index + 1;
            $zip->addFile(Storage::drive('public')->path($image->path), "images/image_$i.jpg");
        }

        $zip->close();

        return response()->download($zipName, $fileName . '.zip');
    }

    protected function createPdf(ClinicalCase $clinicalCase): PDF
    {
        $pdf = app('dompdf.wrapper');

        $pdf->loadView('pdfs.clinical-case', [
            'clinicalCase' => $clinicalCase,
            'titleFieldName' => $this->titleFieldName(),
        ]);

        return $pdf;
    }

    protected function fileName(ClinicalCase $clinicalCase): string
    {
        return Str::snake($clinicalCase->{$this->titleFieldName()});
    }

    protected function titleFieldName(): string
    {
        return config('cc.clinical_case_detail_page.title_field_name');
    }
}
