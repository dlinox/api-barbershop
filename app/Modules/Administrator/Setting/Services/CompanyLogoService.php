<?php

namespace App\Modules\Administrator\Setting\Services;

use App\Common\Helpers\FileHelper;
use App\Modules\Administrator\Setting\Repositories\CompanyRepository;

class CompanyLogoService
{
    private const DISK = 'company_logos';
    private const MAX_SIZE = 2 * 1024 * 1024; // 2 MB

    public function __construct(
        private CompanyRepository $companyRepository
    ) {}

    public function uploadLogo(string $base64Image): string
    {
        $imageData = $this->decodeBase64($base64Image);
        $extension = $this->getExtension($imageData['mime']);

        $this->validateSize($imageData['content']);

        $company = $this->companyRepository->getFirst();

        if ($company && $company->logo) {
            $this->deleteOldLogo($company->logo);
        }

        $filename = FileHelper::generateUniqueFilename('logo', $extension);
        FileHelper::saveFile($imageData['content'], $filename, self::DISK);

        $logoUrl = FileHelper::getFileUrl(self::DISK, $filename);

        $this->companyRepository->save(['logo' => $logoUrl]);

        return $logoUrl;
    }

    public function deleteLogo(): void
    {
        $company = $this->companyRepository->getFirst();

        if ($company && $company->logo) {
            $this->deleteOldLogo($company->logo);
            $this->companyRepository->save(['logo' => null]);
        }
    }

    private function decodeBase64(string $base64Image): array
    {
        if (preg_match('/^data:(image\/\w+);base64,/', $base64Image, $matches)) {
            $mime = $matches[1];
            $content = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $base64Image));
        } else {
            $content = base64_decode($base64Image);
            $mime = 'image/png';
        }

        if ($content === false) {
            throw new \InvalidArgumentException('La imagen no es válida');
        }

        return ['content' => $content, 'mime' => $mime];
    }

    private function getExtension(string $mime): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];

        return $extensions[$mime] ?? 'png';
    }

    private function validateSize(string $content): void
    {
        if (strlen($content) > self::MAX_SIZE) {
            throw new \InvalidArgumentException('La imagen no debe superar los 2 MB');
        }
    }

    private function deleteOldLogo(string $logoUrl): void
    {
        $filename = basename($logoUrl);
        if (FileHelper::fileExists(self::DISK, $filename)) {
            FileHelper::deleteFile(self::DISK, $filename);
        }
    }
}
