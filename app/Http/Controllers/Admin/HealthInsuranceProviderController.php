<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthInsuranceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use ZipArchive;

class HealthInsuranceProviderController extends Controller
{
    /**
     * Display all health insurance providers.
     */
    public function index()
    {
        $providers = HealthInsuranceProvider::orderBy('display_order')
            ->orderByDesc('id')
            ->get();

        return view(
            'admin.health_insurance_providers.index',
            compact('providers')
        );
    }


    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'admin.health_insurance_providers.create'
        );
    }


    /**
     * Store new provider.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'type' => 'required|string|max:100',

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:health_insurance_providers,slug',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'description' => 'nullable|string',

            'website_url' => 'nullable|string|max:500',

            'support_email' => 'nullable|email|max:255',

            'support_phone' => 'nullable|string|max:30',

            'display_order' => 'nullable|integer|min:0',

            'status' => 'nullable|boolean',
        ]);


        $data = $request->except('logo');


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }


        /*
        |--------------------------------------------------------------------------
        | Logo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $folder = public_path(
                'uploads/health-insurance-providers'
            );

            if (!File::exists($folder)) {
                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }


            $file = $request->file('logo');

            $fileName =
                time() . '_' .
                Str::slug(
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    )
                ) .
                '.' .
                $file->getClientOriginalExtension();


            $file->move(
                $folder,
                $fileName
            );


            $data['logo'] =
                'uploads/health-insurance-providers/' . $fileName;
        }


        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $data['display_order'] =
            $request->input('display_order', 0);

        $data['status'] =
            $request->boolean('status', true);


        /*
        |--------------------------------------------------------------------------
        | Create Provider
        |--------------------------------------------------------------------------
        */

        HealthInsuranceProvider::create($data);


        return redirect()
            ->route('admin.health-insurance-providers.index')
            ->with(
                'success',
                'Health Insurance Provider created successfully.'
            );
    }


    /**
     * Display single provider.
     */
    public function show($id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);

        return view(
            'admin.health_insurance_providers.show',
            compact('provider')
        );
    }


    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);

        return view(
            'admin.health_insurance_providers.edit',
            compact('provider')
        );
    }


    /**
     * Update provider.
     */
    public function update(Request $request, $id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);


        $request->validate([
            'name' => 'required|string|max:255',

            'type' => 'required|string|max:100',

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:health_insurance_providers,slug,' . $provider->id,
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'description' => 'nullable|string',

            'website_url' => 'nullable|string|max:500',

            'support_email' => 'nullable|email|max:255',

            'support_phone' => 'nullable|string|max:30',

            'display_order' => 'nullable|integer|min:0',

            'status' => 'nullable|boolean',
        ]);


        $data = $request->except('logo');


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }


        /*
        |--------------------------------------------------------------------------
        | Logo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $folder = public_path(
                'uploads/health-insurance-providers'
            );

            if (!File::exists($folder)) {
                File::makeDirectory(
                    $folder,
                    0755,
                    true
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Delete Old Logo
            |--------------------------------------------------------------------------
            */

            if (
                !empty($provider->logo) &&
                File::exists(
                    public_path($provider->logo)
                )
            ) {
                File::delete(
                    public_path($provider->logo)
                );
            }


            $file = $request->file('logo');

            $fileName =
                time() . '_' .
                Str::slug(
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    )
                ) .
                '.' .
                $file->getClientOriginalExtension();


            $file->move(
                $folder,
                $fileName
            );


            $data['logo'] =
                'uploads/health-insurance-providers/' . $fileName;
        }


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $data['display_order'] =
            $request->input('display_order', 0);

        $data['status'] =
            $request->boolean('status', false);


        /*
        |--------------------------------------------------------------------------
        | Update Provider
        |--------------------------------------------------------------------------
        */

        $provider->update($data);


        return redirect()
            ->route('admin.health-insurance-providers.index')
            ->with(
                'success',
                'Health Insurance Provider updated successfully.'
            );
    }


    /**
     * Delete provider.
     */
    public function destroy($id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Logo
        |--------------------------------------------------------------------------
        */

        if (
            !empty($provider->logo) &&
            File::exists(
                public_path($provider->logo)
            )
        ) {
            File::delete(
                public_path($provider->logo)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Provider
        |--------------------------------------------------------------------------
        */

        $provider->delete();


        return redirect()
            ->route('admin.health-insurance-providers.index')
            ->with(
                'success',
                'Health Insurance Provider deleted successfully.'
            );
    }


    /**
     * Toggle provider status.
     */
    public function toggleStatus($id)
    {
        $provider = HealthInsuranceProvider::findOrFail($id);

        $provider->status = !$provider->status;

        $provider->save();


        return redirect()
            ->back()
            ->with(
                'success',
                'Provider status updated successfully.'
            );
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => ['required', 'file', 'mimes:xlsx', 'max:20480'],
        ]);

        $createdFiles = [];
        $spreadsheet = null;

        try {
            $spreadsheet = IOFactory::load(
                $request->file('excel_file')->getRealPath()
            );

            $sheet = $spreadsheet->getActiveSheet();

            $highestRow = $sheet->getHighestDataRow();
            $highestColumnIndex = Coordinate::columnIndexFromString(
                $sheet->getHighestDataColumn()
            );

            /*
            |--------------------------------------------------------------------------
            | Read Excel headings by name
            |--------------------------------------------------------------------------
            */

            $columnMap = [];

            for ($column = 1; $column <= $highestColumnIndex; $column++) {
                $letter = Coordinate::stringFromColumnIndex($column);

                $heading = Str::snake(
                    trim((string) $sheet->getCell($letter . '1')->getValue())
                );

                if ($heading !== '') {
                    $columnMap[$heading] = $column;
                }
            }

            $requiredColumns = [
                'name',
                'type',
                'slug',
                'logo',
                'description',
                'website_url',
                'support_email',
                'support_phone',
                'display_order',
                'status',
            ];

            $missingColumns = array_diff(
                $requiredColumns,
                array_keys($columnMap)
            );

            if ($missingColumns) {
                return back()->with(
                    'error',
                    'Missing Excel columns: ' . implode(', ', $missingColumns)
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Map embedded logos to cell coordinates
            |--------------------------------------------------------------------------
            */

            $drawingsByCell = [];

            foreach ($sheet->getDrawingCollection() as $drawing) {
                $coordinate = strtoupper($drawing->getCoordinates());

                $drawingsByCell[$coordinate] = $drawing;

                Log::info('Insurance provider Excel drawing detected', [
                    'coordinate' => $coordinate,
                    'class' => get_class($drawing),
                ]);
            }

            $getValue = function (string $heading, int $row) use ($sheet, $columnMap) {
                $letter = Coordinate::stringFromColumnIndex(
                    $columnMap[$heading]
                );

                return $sheet->getCell($letter . $row)->getValue();
            };

            $logoColumn = strtoupper(
                Coordinate::stringFromColumnIndex($columnMap['logo'])
            );

            $imported = 0;
            $skipped = 0;
            $errors = [];

            for ($row = 2; $row <= $highestRow; $row++) {
                $name = trim((string) ($getValue('name', $row) ?? ''));

                if ($name === '') {
                    continue;
                }

                $rowLogoPath = null;

                try {
                    $type = trim((string) ($getValue('type', $row) ?? ''));
                    $slug = trim((string) ($getValue('slug', $row) ?? ''));

                    $description = $getValue('description', $row);
                    $websiteUrl = trim(
                        (string) ($getValue('website_url', $row) ?? '')
                    );
                    $supportEmail = trim(
                        (string) ($getValue('support_email', $row) ?? '')
                    );
                    $supportPhone = trim(
                        (string) ($getValue('support_phone', $row) ?? '')
                    );

                    $displayOrderValue = $getValue('display_order', $row);
                    $statusValue = strtolower(
                        trim((string) ($getValue('status', $row) ?? 'active'))
                    );

                    if ($type === '') {
                        throw new \RuntimeException('Provider type is required.');
                    }

                    if (
                        $displayOrderValue !== null &&
                        $displayOrderValue !== '' &&
                        (
                            !is_numeric($displayOrderValue) ||
                            (int) $displayOrderValue < 0 ||
                            (float) $displayOrderValue != (int) $displayOrderValue
                        )
                    ) {
                        throw new \RuntimeException(
                            'Display order must be a non-negative integer.'
                        );
                    }

                    $booleanValues = [
                        '1' => 1,
                        '0' => 0,
                        'yes' => 1,
                        'no' => 0,
                        'true' => 1,
                        'false' => 0,
                        'active' => 1,
                        'inactive' => 0,
                    ];

                    if (!array_key_exists($statusValue, $booleanValues)) {
                        throw new \RuntimeException(
                            'Status must be active/inactive, yes/no or 1/0.'
                        );
                    }

                    if (
                        $supportEmail !== '' &&
                        !filter_var($supportEmail, FILTER_VALIDATE_EMAIL)
                    ) {
                        throw new \RuntimeException('Invalid support email.');
                    }

                    if (HealthInsuranceProvider::where('name', $name)->exists()) {
                        throw new \RuntimeException(
                            "Provider '{$name}' already exists."
                        );
                    }

                    if ($slug === '') {
                        $slug = Str::slug($name);
                    }

                    if ($slug === '') {
                        throw new \RuntimeException(
                            'Unable to generate a slug for this provider.'
                        );
                    }

                    if (HealthInsuranceProvider::where('slug', $slug)->exists()) {
                        throw new \RuntimeException(
                            "Slug '{$slug}' already exists."
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Extract embedded logo
                    |--------------------------------------------------------------------------
                    */

                    $logoCoordinate = $logoColumn . $row;

                    if (isset($drawingsByCell[$logoCoordinate])) {
                        $rowLogoPath = $this->saveEmbeddedProviderLogo(
                            $drawingsByCell[$logoCoordinate],
                            $createdFiles
                        );

                        if (!$rowLogoPath) {
                            throw new \RuntimeException(
                                'Embedded logo could not be extracted.'
                            );
                        }
                    }

                    HealthInsuranceProvider::create([
                        'name' => $name,
                        'type' => $type,
                        'slug' => $slug,
                        'logo' => $rowLogoPath,
                        'description' => $description ?: null,
                        'website_url' => $websiteUrl ?: null,
                        'support_email' => $supportEmail ?: null,
                        'support_phone' => $supportPhone ?: null,
                        'display_order' => $displayOrderValue === null ||
                            $displayOrderValue === ''
                            ? 0
                            : (int) $displayOrderValue,
                        'status' => $booleanValues[$statusValue],
                    ]);

                    $imported++;

                } catch (\Throwable $e) {
                    // Remove a logo if this row failed after saving it.
                    if ($rowLogoPath) {
                        $absolutePath = public_path($rowLogoPath);

                        if (File::exists($absolutePath)) {
                            File::delete($absolutePath);
                        }

                        $createdFiles = array_values(array_filter(
                            $createdFiles,
                            fn($path) => $path !== $absolutePath
                        ));
                    }

                    $skipped++;
                    $errors[] = "Row {$row}: {$e->getMessage()}";

                    Log::warning('Insurance provider import row failed', [
                        'row' => $row,
                        'name' => $name,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return redirect()
                ->route('admin.health-insurance-providers.index')
                ->with(
                    'success',
                    "{$imported} providers imported successfully. {$skipped} rows skipped."
                )
                ->with('import_errors', $errors);

        } catch (\Throwable $e) {
            // Remove images created by this import if the import itself fails.
            foreach ($createdFiles as $path) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            report($e);

            return back()->with(
                'error',
                'Import failed. Please check the Excel headings and embedded logos.'
            );

        } finally {
            if ($spreadsheet) {
                $spreadsheet->disconnectWorksheets();
            }
        }
    }

    private function saveEmbeddedProviderLogo(
        $drawing,
        array &$createdFiles
    ): ?string {
        try {
            $contents = null;

            if ($drawing instanceof Drawing) {
                $sourcePath = $drawing->getPath();

                if (!$sourcePath) {
                    return null;
                }

                if (is_file($sourcePath)) {
                    $contents = file_get_contents($sourcePath);

                } elseif (str_starts_with($sourcePath, 'zip://')) {
                    $zipPath = substr($sourcePath, strlen('zip://'));
                    $separator = strpos($zipPath, '#');

                    if ($separator === false) {
                        return null;
                    }

                    $archivePath = rawurldecode(
                        substr($zipPath, 0, $separator)
                    );

                    $entryPath = substr($zipPath, $separator + 1);

                    $zip = new ZipArchive();
                    $opened = $zip->open($archivePath);

                    if ($opened !== true) {
                        Log::error('Unable to open insurance provider XLSX.', [
                            'archive' => $archivePath,
                            'code' => $opened,
                        ]);

                        return null;
                    }

                    try {
                        $contents = $zip->getFromName($entryPath);
                    } finally {
                        $zip->close();
                    }
                }

            } elseif ($drawing instanceof MemoryDrawing) {
                $resource = $drawing->getImageResource();
                $renderer = $drawing->getRenderingFunction();

                if (!$resource || !$renderer) {
                    return null;
                }

                ob_start();

                try {
                    call_user_func($renderer, $resource);
                    $contents = ob_get_contents();
                } finally {
                    ob_end_clean();
                }
            }

            if (!is_string($contents) || $contents === '') {
                return null;
            }

            $imageInfo = @getimagesizefromstring($contents);

            if (!$imageInfo || empty($imageInfo['mime'])) {
                return null;
            }

            $formats = [
                'image/png' => 'png',
                'image/jpeg' => 'jpg',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
            ];

            $extension = $formats[$imageInfo['mime']] ?? null;

            if (!$extension) {
                Log::warning('Unsupported insurance provider logo format.', [
                    'mime' => $imageInfo['mime'],
                ]);

                return null;
            }

            $relativeDirectory = 'uploads/health-insurance-providers';
            $absoluteDirectory = public_path($relativeDirectory);

            File::ensureDirectoryExists($absoluteDirectory);

            $filename = Str::uuid() . '.' . $extension;
            $absolutePath = $absoluteDirectory . DIRECTORY_SEPARATOR . $filename;

            if (File::put($absolutePath, $contents) !== strlen($contents)) {
                Log::error('Failed to save insurance provider logo.', [
                    'path' => $absolutePath,
                ]);

                return null;
            }

            $createdFiles[] = $absolutePath;

            Log::info('Insurance provider logo saved.', [
                'coordinate' => $drawing->getCoordinates(),
                'path' => $relativeDirectory . '/' . $filename,
            ]);

            return $relativeDirectory . '/' . $filename;

        } catch (\Throwable $e) {
            Log::error('Insurance provider logo extraction failed.', [
                'message' => $e->getMessage(),
                'coordinate' => method_exists($drawing, 'getCoordinates')
                    ? $drawing->getCoordinates()
                    : null,
            ]);

            return null;
        }
    }
}