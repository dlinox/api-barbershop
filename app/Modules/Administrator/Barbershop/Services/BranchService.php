<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Common\Helpers\FileHelper;
use App\Models\Barbershop\Branch;
use App\Modules\Administrator\Barbershop\Repositories\BranchRepository;
use Illuminate\Http\Request;

class BranchService
{
    private const LOGO_DISK = 'branch_logos';
    private const LOGO_MAX_SIZE = 2 * 1024 * 1024;

    public function __construct(
        private BranchRepository $branchRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->branchRepository->dataTable($request);
    }

    public function save(array $data)
    {
        if (array_key_exists('logo', $data)) {
            if ($data['logo'] !== null) {
                $data['logo'] = $this->processLogo($data['logo'], $data['id'] ?? null);
            } else {
                unset($data['logo']);
            }
        }
        return $this->branchRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->branchRepository->delete($id);
    }

    public function getActiveBranches()
    {
        return $this->branchRepository->getActiveBranches();
    }

    public function deleteLogo(int $id): void
    {
        $branch = Branch::find($id);
        if ($branch && $branch->logo) {
            FileHelper::deleteFile(self::LOGO_DISK, basename($branch->logo));
            $branch->update(['logo' => null]);
        }
    }

    private function processLogo(string $base64Image, ?int $branchId): string
    {
        if ($branchId) {
            $branch = Branch::find($branchId);
            if ($branch && $branch->logo) {
                FileHelper::deleteFile(self::LOGO_DISK, basename($branch->logo));
            }
        }

        $imageData = $this->decodeBase64($base64Image);
        $extension = $this->getExtension($imageData['mime']);
        $filename = FileHelper::generateUniqueFilename('branch-logo', $extension);
        FileHelper::saveFile($imageData['content'], $filename, self::LOGO_DISK);
        return FileHelper::getFileUrl(self::LOGO_DISK, $filename);
    }

    private function decodeBase64(string $base64Image): array
    {
        if (preg_match('/^data:(image\/[\w+]+);base64,/', $base64Image, $matches)) {
            $mime = $matches[1];
            $content = base64_decode(preg_replace('/^data:image\/[\w+]+;base64,/', '', $base64Image));
        } else {
            $content = base64_decode($base64Image);
            $mime = 'image/png';
        }

        if ($content === false) {
            throw new \InvalidArgumentException('La imagen no es valida');
        }

        if (strlen($content) > self::LOGO_MAX_SIZE) {
            throw new \InvalidArgumentException('La imagen no debe superar los 2 MB');
        }

        return ['content' => $content, 'mime' => $mime];
    }

    private function getExtension(string $mime): string
    {
        return [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ][$mime] ?? 'png';
    }
}