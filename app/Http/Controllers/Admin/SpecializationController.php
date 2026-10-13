<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HospitalSpecialization;
use App\Models\Specialization;
use App\Models\SpecializationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Specialization::query();

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('specialization_name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $specializations = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();
        $categories = SpecializationCategory::where('status', 1)
            ->orderBy('category_name')
            ->get();

        return view(
            'admin.specializations.index',
            compact('specializations', 'categories')
        );
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = SpecializationCategory::where('status', 1)
            ->orderBy('category_name', 'asc')
            ->get();
        return view('admin.specializations.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'specialization_name' => 'required|string|max:255|unique:specializations,specialization_name',
            'specialization_category' => 'required|exists:specialization_categories,id',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $specialization = new Specialization();

        $specialization->specialization_name = $request->specialization_name;
        $specialization->specialization_category = $request->specialization_category;
        $specialization->slug = Str::slug($request->specialization_name);
        $specialization->description = $request->description;
        $specialization->status = $request->status;

        if ($request->hasFile('icon')) {

            $icon = $request->file('icon');
            $iconName = time() . '_icon.' . $icon->getClientOriginalExtension();

            $icon->move(public_path('uploads/specializations/icons'), $iconName);

            $specialization->icon = 'uploads/specializations/icons/' . $iconName;
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/specializations/images'), $imageName);

            $specialization->image = 'uploads/specializations/images/' . $imageName;
        }

        $specialization->save();

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Specialization created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $specialization = Specialization::findOrFail($id);

        return view('admin.specializations.show', compact('specialization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $specialization = Specialization::findOrFail($id);
        $categories = SpecializationCategory::where('status', 1)
            ->orderBy('category_name', 'asc')
            ->get();

        return view('admin.specializations.edit', compact('specialization', 'categories'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        $specialization = Specialization::findOrFail($id);

        $request->validate([
            'specialization_name' => 'required|string|max:255|unique:specializations,specialization_name,' . $specialization->id,
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $specialization->specialization_name = $request->specialization_name;
        $specialization->slug = Str::slug($request->specialization_name);
        $specialization->description = $request->description;
        $specialization->status = $request->status;
        $specialization->specialization_category = $request->specialization_category;

        if ($request->hasFile('icon')) {

            if ($specialization->icon && File::exists(public_path($specialization->icon))) {
                File::delete(public_path($specialization->icon));
            }

            $icon = $request->file('icon');
            $iconName = time() . '_icon.' . $icon->getClientOriginalExtension();

            $icon->move(public_path('uploads/specializations/icons'), $iconName);

            $specialization->icon = 'uploads/specializations/icons/' . $iconName;
        }

        if ($request->hasFile('image')) {

            if ($specialization->image && File::exists(public_path($specialization->image))) {
                File::delete(public_path($specialization->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/specializations/images'), $imageName);

            $specialization->image = 'uploads/specializations/images/' . $imageName;
        }

        $specialization->save();

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Specialization updated successfully.');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $specialization = Specialization::findOrFail($id);
        $isUsed = HospitalSpecialization::where('specialization_id', $specialization->id)->exists();

        if ($isUsed) {
            return redirect()
                ->route('admin.specializations.index')
                ->with('error', 'This specialization is assigned to one or more hospitals and cannot be deleted.');
        }
        if ($specialization->icon && File::exists(public_path($specialization->icon))) {
            File::delete(public_path($specialization->icon));
        }

        if ($specialization->image && File::exists(public_path($specialization->image))) {
            File::delete(public_path($specialization->image));
        }

        $specialization->delete();

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Specialization deleted successfully.');
    }

    /**
     * Change Status
     */
    public function status($id)
    {
        $specialization = Specialization::findOrFail($id);

        $specialization->status = !$specialization->status;

        $specialization->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
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
            $highestColumn = $sheet->getHighestDataColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString(
                $highestColumn
            );

            /*
            |--------------------------------------------------------------------------
            | Read Column Headings Dynamically
            |--------------------------------------------------------------------------
            */

            $columnMap = [];

            for ($column = 1; $column <= $highestColumnIndex; $column++) {
                $columnLetter = Coordinate::stringFromColumnIndex($column);

                $heading = $sheet->getCell($columnLetter . '1')->getValue();

                $heading = Str::snake(trim((string) $heading));

                if ($heading !== '') {
                    $columnMap[$heading] = $column;
                }
            }

            $requiredColumns = [
                'specialization_name',
                'category_name',
                'description',
                'status',
                'icon',
                'image',
            ];

            $missingColumns = array_diff(
                $requiredColumns,
                array_keys($columnMap)
            );

            if (!empty($missingColumns)) {
                return back()->with(
                    'error',
                    'Missing Excel columns: ' . implode(', ', $missingColumns)
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Map Embedded Images Using Heading Names
            |--------------------------------------------------------------------------
            */

            $drawingsByCell = [];

            foreach ($sheet->getDrawingCollection() as $drawing) {
                $coordinate = strtoupper($drawing->getCoordinates());

                Log::info('Specialization Excel drawing detected', [
                    'coordinate' => $coordinate,
                    'class' => get_class($drawing),
                ]);

                $drawingsByCell[$coordinate] = $drawing;
            }

            Log::info('Specialization Excel drawing summary', [
                'total_drawings' => count($drawingsByCell),
                'coordinates' => array_keys($drawingsByCell),
            ]);

            $imported = 0;
            $skipped = 0;
            $errors = [];

            $categories = SpecializationCategory::all()
                ->keyBy(
                    fn($category) =>
                        Str::lower(trim($category->category_name))
                );

            /*
            |--------------------------------------------------------------------------
            | Process Rows
            |--------------------------------------------------------------------------
            */

            for ($row = 2; $row <= $highestRow; $row++) {
                $getValue = function (string $heading) use ($sheet, $columnMap, $row) {
                    $columnLetter = Coordinate::stringFromColumnIndex(
                        $columnMap[$heading]
                    );

                    return $sheet->getCell(
                        $columnLetter . $row
                    )->getValue();
                };

                $name = trim((string) ($getValue('specialization_name') ?? ''));
                $categoryName = trim((string) ($getValue('category_name') ?? ''));
                $description = $getValue('description');
                $statusValue = $getValue('status');

                if ($name === '' && $categoryName === '') {
                    continue;
                }

                if ($name === '' || $categoryName === '') {
                    $skipped++;
                    $errors[] = "Row {$row}: Specialization name and category are required.";
                    continue;
                }

                $category = $categories->get(Str::lower($categoryName));

                // if (!$category) {
                //     $skipped++;
                //     $errors[] = "Row {$row}: Category '{$categoryName}' does not exist.";
                //     continue;
                // }

                if (
                    Specialization::where(
                        'specialization_name',
                        $name
                    )->exists()
                ) {
                    $skipped++;
                    $errors[] = "Row {$row}: '{$name}' already exists.";
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Get Image Coordinates From Heading Names
                |--------------------------------------------------------------------------
                */

                $iconColumn = strtoupper(
                    Coordinate::stringFromColumnIndex(
                        $columnMap['icon']
                    )
                );

                $imageColumn = strtoupper(
                    Coordinate::stringFromColumnIndex(
                        $columnMap['image']
                    )
                );

                $iconPath = null;
                $imagePath = null;

                $iconCoordinate = $iconColumn . $row;
                $imageCoordinate = $imageColumn . $row;

                if (isset($drawingsByCell[$iconCoordinate])) {
                    $iconPath = $this->saveEmbeddedSpecializationImage(
                        $drawingsByCell[$iconCoordinate],
                        'icons',
                        $createdFiles
                    );

                    if (!$iconPath) {
                        $errors[] = "Row {$row}: Unable to extract icon.";
                    }
                }

                if (isset($drawingsByCell[$imageCoordinate])) {
                    $imagePath = $this->saveEmbeddedSpecializationImage(
                        $drawingsByCell[$imageCoordinate],
                        'images',
                        $createdFiles
                    );

                    if (!$imagePath) {
                        $errors[] = "Row {$row}: Unable to extract image.";
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Save Specialization
                |--------------------------------------------------------------------------
                */

                $status = in_array(
                    Str::lower(trim((string) $statusValue)),
                    ['0', 'inactive', 'false', 'no'],
                    true
                ) ? 0 : 1;

                Specialization::create([
                    'specialization_name' => $name,
                    'specialization_category' => $category->id ?? null,
                    'slug' => Str::slug($name),
                    'description' => $description ?: null,
                    'status' => $status,
                    'icon' => $iconPath,
                    'image' => $imagePath,
                ]);

                $imported++;
            }

            return redirect()
                ->route('admin.specializations.index')
                ->with(
                    'success',
                    "{$imported} specializations imported successfully. {$skipped} rows skipped."
                )
                ->with('import_errors', $errors);

        } catch (\Throwable $e) {
            foreach ($createdFiles as $path) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            report($e);

            return back()->with(
                'error',
                'Import failed. Please check the Excel headings and embedded images.'
            );
        } finally {
            if ($spreadsheet) {
                $spreadsheet->disconnectWorksheets();
            }
        }
    }


    private function saveEmbeddedSpecializationImage(
        $drawing,
        string $type,
        array &$createdFiles
    ): ?string {
        try {
            $contents = null;

            /*
            |--------------------------------------------------------------------------
            | Read Embedded Image
            |--------------------------------------------------------------------------
            */

            if ($drawing instanceof Drawing) {
                $sourcePath = $drawing->getPath();

                Log::info('Reading specialization image', [
                    'coordinate' => $drawing->getCoordinates(),
                    'path' => $sourcePath,
                ]);

                if (!$sourcePath) {
                    Log::warning('Specialization drawing has no source path.');
                    return null;
                }

                // Normal filesystem image
                if (is_file($sourcePath)) {
                    $contents = file_get_contents($sourcePath);
                }

                // Embedded image inside an XLSX ZIP archive
                elseif (str_starts_with($sourcePath, 'zip://')) {
                    $zipPath = substr($sourcePath, strlen('zip://'));

                    // PhpSpreadsheet format: archive.xlsx#xl/media/image1.png
                    $separatorPosition = strpos($zipPath, '#');

                    if ($separatorPosition === false) {
                        Log::error('Invalid XLSX embedded image path.', [
                            'path' => $sourcePath,
                        ]);

                        return null;
                    }

                    $archivePath = substr($zipPath, 0, $separatorPosition);
                    $entryPath = substr($zipPath, $separatorPosition + 1);

                    // Decode URL-encoded temporary paths if present
                    $archivePath = rawurldecode($archivePath);

                    $zip = new ZipArchive();

                    $opened = $zip->open($archivePath);

                    if ($opened !== true) {
                        Log::error('Unable to open XLSX archive.', [
                            'archive' => $archivePath,
                            'zip_error_code' => $opened,
                        ]);

                        return null;
                    }

                    try {
                        $contents = $zip->getFromName($entryPath);
                    } finally {
                        $zip->close();
                    }

                    if ($contents === false || $contents === '') {
                        Log::error('Embedded image not found in XLSX archive.', [
                            'entry' => $entryPath,
                            'archive' => $archivePath,
                        ]);

                        return null;
                    }
                } else {
                    Log::error('Unsupported specialization image source.', [
                        'path' => $sourcePath,
                    ]);

                    return null;
                }

            } elseif ($drawing instanceof MemoryDrawing) {
                $resource = $drawing->getImageResource();
                $renderFunction = $drawing->getRenderingFunction();

                if (!$resource || !$renderFunction) {
                    Log::error('Invalid MemoryDrawing resource.');
                    return null;
                }

                ob_start();

                try {
                    call_user_func($renderFunction, $resource);
                    $contents = ob_get_contents();
                } finally {
                    ob_end_clean();
                }

            } else {
                Log::warning('Unsupported Excel drawing class.', [
                    'class' => get_class($drawing),
                ]);

                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | Validate Image Contents
            |--------------------------------------------------------------------------
            */

            if (!$contents) {
                Log::error('Specialization image contents are empty.');
                return null;
            }

            $imageInfo = @getimagesizefromstring($contents);

            if (!$imageInfo || empty($imageInfo['mime'])) {
                Log::error('Embedded file is not a supported image.');
                return null;
            }

            $allowedFormats = [
                'image/png' => 'png',
                'image/jpeg' => 'jpg',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
            ];

            $extension = $allowedFormats[$imageInfo['mime']] ?? null;

            if (!$extension) {
                Log::warning('Unsupported specialization image format.', [
                    'mime' => $imageInfo['mime'],
                ]);

                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | Save Image
            |--------------------------------------------------------------------------
            */

            if (!in_array($type, ['icons', 'images'], true)) {
                Log::error('Invalid specialization image directory.', [
                    'type' => $type,
                ]);

                return null;
            }

            $relativeDirectory = "uploads/specializations/{$type}";
            $absoluteDirectory = public_path($relativeDirectory);

            File::ensureDirectoryExists($absoluteDirectory);

            $filename = Str::uuid() . '.' . $extension;

            $absolutePath = $absoluteDirectory . DIRECTORY_SEPARATOR . $filename;

            $bytesWritten = File::put($absolutePath, $contents);

            if ($bytesWritten === false || $bytesWritten !== strlen($contents)) {
                Log::error('Failed to write specialization image.', [
                    'path' => $absolutePath,
                ]);

                return null;
            }

            $createdFiles[] = $absolutePath;

            Log::info('Specialization image saved successfully.', [
                'type' => $type,
                'path' => $relativeDirectory . '/' . $filename,
                'size' => $bytesWritten,
            ]);

            return $relativeDirectory . '/' . $filename;

        } catch (\Throwable $e) {
            Log::error('Specialization image extraction failed.', [
                'coordinate' => method_exists($drawing, 'getCoordinates')
                    ? $drawing->getCoordinates()
                    : null,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return null;
        }
    }

}