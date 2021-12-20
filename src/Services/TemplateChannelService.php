<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Templates\Contracts\TemplateChannelContract;
use EscolaLms\Templates\Enums\TemplateSectionTypeEnum;
use EscolaLms\Templates\Services\Contracts\TemplateChannelServiceContract;
use Exception;
use InvalidArgumentException;
use EscolaLms\Templates\Core\TemplateSectionSchema;

class TemplateChannelService implements TemplateChannelServiceContract
{
    protected static array $channels = [];

    public function register(string $class): void
    {
        if (!is_a($class, TemplateChannelContract::class, true)) {
            throw new InvalidArgumentException(__("Invalid argument. :expected expected, :given given.", ['expected' => TemplateChannelContract::class, 'given' => $class]));
        }

        if (in_array($class, self::$channels)) {
            return;
        }

        foreach ($class::sections() as $sectionSchema) {
            if (!$sectionSchema instanceof TemplateSectionSchema) {
                throw new Exception('Error in Template Channel Class in `sections()` method');
            }
        }
        foreach ($class::sectionsRequired() as $key) {
            if (!$class::sections()->where('key', $key)->first()) {
                throw new Exception('Error in Template Channel Class in `sectionsRequired()` method');
            }
        }

        self::$channels[] = $class;
    }

    public function list(): array
    {
        return self::$channels;
    }

    public function validateTemplateSections(string $class, array $sections): bool
    {
        if (!in_array($class, self::$channels)) {
            return false;
        }
        foreach ($sections as $section => $content) {
            if (in_array($section, $class::sectionsRequired()) && empty($content)) {
                return false;
            }
            switch ($class::section($section)->getType()) {
                case TemplateSectionTypeEnum::SECTION_HTML():
                case TemplateSectionTypeEnum::SECTION_MJML():
                    if ($content === strip_tags($content)) {
                        return false;
                    }
                    break;
                case TemplateSectionTypeEnum::SECTION_TEXT():
                    if ($content !== strip_tags($content)) {
                        return false;
                    }
                    break;
                case TemplateSectionTypeEnum::SECTION_URL():
                    if (!filter_var($content, FILTER_VALIDATE_URL)) {
                        return false;
                    }
                    break;
                default:
                    break;
            }
        }
        return true;
    }
}
