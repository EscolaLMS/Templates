<?php

namespace EscolaLms\Templates\Jobs;

use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Models\Certificate;
use EscolaLms\Templates\Enum\CertificateVar;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Services\TemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Course $course;
    protected User $user;
    protected TemplateServiceContract $templateService;

    public function __construct(Course $course, User $user, TemplateServiceContract $templateService)
    {
        $this->course = $course;
        $this->user = $user;
        $this->templateService = $templateService;
    }

    public function handle()
    {
        $template = Template::where('course_id', $this->course->id)->first();

        if (empty($template)) {
            return true;
        }

        $certificate = Certificate::firstOrCreate([
            'user_id' => $this->user->id,
            'course_id' => $this->course->id,
            'template_id' => $template->id
        ]);

        $certificate->update([
            'status'=>'pending',
            'path' => ''
        ]);

        $vars = CertificateVar::getVariablesFromContent($this->course, $this->user);

        $filepath = $this->templateService->generatePDF($template, $this->user);

        $certificate->update([
            'status'=>'finished',
            'path' =>  $filepath
        ]);


        return true;
    }
}
