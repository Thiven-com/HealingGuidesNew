<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use ZipArchive;

class LabTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $labTests = LabTest::query();

        // Search
        if ($request->filled('search')) {

            $search = trim($request->search);

            $labTests->where(function ($query) use ($search) {

                $query->where('test_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('test_code', 'LIKE', '%' . $search . '%')
                    ->orWhere('sample_type', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');

            });
        }

        // Fasting Required
        if ($request->filled('fasting_required')) {

            $labTests->where(
                'fasting_required',
                $request->fasting_required
            );
        }

        // Home Collection
        if ($request->filled('home_collection')) {

            $labTests->where(
                'home_collection',
                $request->home_collection
            );
        }

        // Status
        if ($request->filled('status')) {

            $labTests->where(
                'status',
                $request->status
            );
        }

        // Pagination
        $labTests = $labTests
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.lab-tests.index',
            compact('labTests')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.lab-tests.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'test_name' => 'required|string|max:255|unique:lab_tests,test_name',
            'description' => 'nullable|string',
            'sample_type' => 'required|string|max:255',
            'preparation' => 'nullable|string',
            'report_time' => 'required|numeric|min:1',
            'report_time_type' => 'required|in:Hours,Days',
            'fasting_required' => 'required|boolean',
            'home_collection' => 'required|boolean',
            'status' => 'required|boolean',
        ]);
        $imagePath = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . '_' . Str::slug($request->test_name) . '.' . $image->getClientOriginalExtension();

            $destination = public_path('uploads/lab-tests');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $image->move($destination, $imageName);

            $imagePath = 'uploads/lab-tests/' . $imageName;
        }
        // Generate Test Code
        $lastTest = LabTest::latest()->first();

        if ($lastTest && $lastTest->test_code) {

            $number = (int) substr($lastTest->test_code, 2);

            $number++;

        } else {

            $number = 1;

        }

        $testCode = 'LT' . str_pad($number, 5, '0', STR_PAD_LEFT);

        // Create Lab Test
        LabTest::create([

            'test_name' => $request->test_name,

            'test_code' => $testCode,

            'slug' => Str::slug($request->test_name),

            'description' => $request->description,

            'sample_type' => $request->sample_type,

            'preparation' => $request->preparation,

            'report_time' => $request->report_time,

            'report_time_type' => $request->report_time_type,

            'fasting_required' => $request->fasting_required,

            'home_collection' => $request->home_collection,

            'status' => $request->status,
            'image' => $imagePath,

        ]);

        return redirect()
            ->route('admin.lab-tests.index')
            ->with('success', 'Lab Test Created Successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $labTest = LabTest::findOrFail($id);

        return view('admin.lab-tests.show', compact('labTest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $labTest = LabTest::findOrFail($id);

        return view('admin.lab-tests.edit', compact('labTest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $labTest = LabTest::findOrFail($id);

        $request->validate([
            'test_name' => 'required|string|max:255|unique:lab_tests,test_name,' . $labTest->id,
            'description' => 'nullable|string',
            'sample_type' => 'required|string|max:255',
            'preparation' => 'nullable|string',
            'report_time' => 'required|numeric|min:1',
            'report_time_type' => 'required|in:Hours,Days',
            'fasting_required' => 'required|boolean',
            'home_collection' => 'required|boolean',
            'status' => 'required|boolean',
        ]);
        $imagePath = $labTest->image;

        if ($request->hasFile('image')) {

            if ($labTest->image && File::exists(public_path($labTest->image))) {
                File::delete(public_path($labTest->image));
            }

            $image = $request->file('image');

            $imageName = time() . '_' . Str::slug($request->test_name) . '.' . $image->getClientOriginalExtension();

            $destination = public_path('uploads/lab-tests');

            if (!File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }

            $image->move($destination, $imageName);

            $imagePath = 'uploads/lab-tests/' . $imageName;
        }

        $labTest->update([

            'test_name' => $request->test_name,

            'slug' => Str::slug($request->test_name),

            'description' => $request->description,

            'sample_type' => $request->sample_type,

            'preparation' => $request->preparation,

            'report_time' => $request->report_time,

            'report_time_type' => $request->report_time_type,

            'fasting_required' => $request->fasting_required,

            'home_collection' => $request->home_collection,

            'status' => $request->status,
            'image' => $imagePath,

        ]);

        return redirect()
            ->route('admin.lab-tests.index')
            ->with('success', 'Lab Test Updated Successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $labTest = LabTest::findOrFail($id);

        $labTest->delete();

        return redirect()
            ->route('admin.lab-tests.index')
            ->with('success', 'Lab Test Deleted Successfully.');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function status(string $id)
    {
        $labTest = LabTest::findOrFail($id);

        $labTest->status = $labTest->status ? 0 : 1;

        $labTest->save();

        return redirect()
            ->route('admin.lab-tests.index')
            ->with('success', 'Lab Test Status Updated Successfully.');
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
            | Read headings dynamically
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
                'test_name',
                'test_code',
                'slug',
                'image',
                'description',
                'sample_type',
                'preparation',
                'report_time',
                'report_time_type',
                'fasting_required',
                'home_collection',
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
            | Map embedded images to their Excel coordinates
            |--------------------------------------------------------------------------
            */

            $drawingsByCell = [];

            foreach ($sheet->getDrawingCollection() as $drawing) {
                $coordinate = strtoupper($drawing->getCoordinates());

                $drawingsByCell[$coordinate] = $drawing;

                Log::info('Lab test Excel drawing detected', [
                    'coordinate' => $coordinate,
                    'class' => get_class($drawing),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Read values using heading names
            |--------------------------------------------------------------------------
            */

            $getValue = function (string $heading, int $row) use ($sheet, $columnMap) {
                $letter = Coordinate::stringFromColumnIndex(
                    $columnMap[$heading]
                );

                return $sheet->getCell($letter . $row)->getValue();
            };

            $imageColumn = strtoupper(
                Coordinate::stringFromColumnIndex($columnMap['image'])
            );

            $imported = 0;
            $skipped = 0;
            $errors = [];

            for ($row = 2; $row <= $highestRow; $row++) {
                $name = trim((string) ($getValue('test_name', $row) ?? ''));

                if ($name === '') {
                    continue;
                }

                try {
                    $testCode = trim(
                        (string) ($getValue('test_code', $row) ?? '')
                    );

                    $slug = trim(
                        (string) ($getValue('slug', $row) ?? '')
                    );

                    $description = $getValue('description', $row);
                    $sampleType = trim(
                        (string) ($getValue('sample_type', $row) ?? '')
                    );

                    $preparation = $getValue('preparation', $row);
                    $reportTime = $getValue('report_time', $row);

                    $reportTimeType = ucfirst(
                        strtolower(
                            trim((string) ($getValue('report_time_type', $row) ?? ''))
                        )
                    );

                    $fastingValue = strtolower(
                        trim((string) ($getValue('fasting_required', $row) ?? ''))
                    );

                    $homeCollectionValue = strtolower(
                        trim((string) ($getValue('home_collection', $row) ?? ''))
                    );

                    $statusValue = strtolower(
                        trim((string) ($getValue('status', $row) ?? ''))
                    );

                    // Validate required data
                    if ($sampleType === '') {
                        throw new \RuntimeException('Sample type is required.');
                    }

                    if (!is_numeric($reportTime) || (float) $reportTime < 1) {
                        throw new \RuntimeException(
                            'Report time must be a number greater than or equal to 1.'
                        );
                    }

                    if (!in_array($reportTimeType, ['Hours', 'Days'], true)) {
                        throw new \RuntimeException(
                            'Report time type must be Hours or Days.'
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

                    if (
                        !array_key_exists($fastingValue, $booleanValues) ||
                        !array_key_exists($homeCollectionValue, $booleanValues) ||
                        !array_key_exists($statusValue, $booleanValues)
                    ) {
                        throw new \RuntimeException(
                            'Fasting, home collection and status must use yes/no, true/false or 1/0.'
                        );
                    }

                    if (LabTest::where('test_name', $name)->exists()) {
                        throw new \RuntimeException(
                            "Lab test '{$name}' already exists."
                        );
                    }

                    // Generate test code if the cell is empty
                    if ($testCode === '') {
                        $lastTest = LabTest::where(
                            'test_code',
                            'LIKE',
                            'LT%'
                        )->orderByDesc('id')->first();

                        $number = $lastTest
                            ? ((int) substr($lastTest->test_code, 2)) + 1
                            : 1;

                        do {
                            $testCode = 'LT' . str_pad(
                                $number++,
                                5,
                                '0',
                                STR_PAD_LEFT
                            );
                        } while (LabTest::where('test_code', $testCode)->exists());
                    } elseif (LabTest::where('test_code', $testCode)->exists()) {
                        throw new \RuntimeException(
                            "Test code '{$testCode}' already exists."
                        );
                    }

                    // Generate slug if the cell is empty
                    if ($slug === '') {
                        $slug = Str::slug($name);
                    }

                    // Find image anchored to this row in the image column
                    $imagePath = null;
                    $imageCoordinate = $imageColumn . $row;

                    if (isset($drawingsByCell[$imageCoordinate])) {
                        $imagePath = $this->saveEmbeddedLabTestImage(
                            $drawingsByCell[$imageCoordinate],
                            $createdFiles
                        );

                        if (!$imagePath) {
                            $errors[] = "Row {$row}: Image could not be extracted for '{$name}'.";
                        }
                    }

                    LabTest::create([
                        'test_name' => $name,
                        'test_code' => $testCode,
                        'slug' => $slug,
                        'image' => $imagePath,
                        'description' => $description ?: null,
                        'sample_type' => $sampleType,
                        'preparation' => $preparation ?: null,
                        'report_time' => $reportTime,
                        'report_time_type' => $reportTimeType,
                        'fasting_required' => $booleanValues[$fastingValue],
                        'home_collection' => $booleanValues[$homeCollectionValue],
                        'status' => $booleanValues[$statusValue],
                    ]);

                    $imported++;

                } catch (\Throwable $e) {
                    $skipped++;

                    $errors[] = "Row {$row}: {$e->getMessage()}";

                    Log::warning('Lab test Excel row skipped', [
                        'row' => $row,
                        'test_name' => $name,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return redirect()
                ->route('admin.lab-tests.index')
                ->with(
                    'success',
                    "{$imported} lab tests imported successfully. {$skipped} rows skipped."
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
                'Import failed: ' . $e->getMessage()
            );

        } finally {
            if ($spreadsheet) {
                $spreadsheet->disconnectWorksheets();
            }
        }
    }

    private function saveEmbeddedLabTestImage(
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
                        Log::error('Unable to open lab test XLSX archive.', [
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
                Log::warning('Lab test embedded image contents are empty.');
                return null;
            }

            $imageInfo = @getimagesizefromstring($contents);

            if (!$imageInfo || empty($imageInfo['mime'])) {
                Log::warning('Invalid embedded lab test image.');
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
                Log::warning('Unsupported lab test image format.', [
                    'mime' => $imageInfo['mime'],
                ]);

                return null;
            }

            $relativeDirectory = 'uploads/lab-tests';
            $absoluteDirectory = public_path($relativeDirectory);

            File::ensureDirectoryExists($absoluteDirectory);

            $filename = Str::uuid() . '.' . $extension;
            $absolutePath = $absoluteDirectory . DIRECTORY_SEPARATOR . $filename;

            if (File::put($absolutePath, $contents) !== strlen($contents)) {
                Log::error('Failed to write lab test image.', [
                    'path' => $absolutePath,
                ]);

                return null;
            }

            $createdFiles[] = $absolutePath;

            Log::info('Lab test image saved successfully.', [
                'coordinate' => $drawing->getCoordinates(),
                'path' => $relativeDirectory . '/' . $filename,
            ]);

            return $relativeDirectory . '/' . $filename;

        } catch (\Throwable $e) {
            Log::error('Lab test image extraction failed.', [
                'message' => $e->getMessage(),
                'coordinate' => method_exists($drawing, 'getCoordinates')
                    ? $drawing->getCoordinates()
                    : null,
            ]);

            return null;
        }
    }
}