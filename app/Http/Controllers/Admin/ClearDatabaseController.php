<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ClearDatabaseController extends Controller
{
    public function index(): View
    {
        return view('admin.clear-database.index');
    }

    public function clearDB()
    {
        try {
            //? wipe database
            Artisan::call('migrate:fresh');

            //? seed default data
            Artisan::call('db:seed', ['--class' => 'UserSeeder']);
            Artisan::call('db:seed', ['--class' => 'SettingSeeder']);
            Artisan::call('db:seed', ['--class' => 'PaymentGatewaySettingsSeeder']);
            Artisan::call('db:seed', ['--class' => 'SectionTitlesSeeder']);
            Artisan::call('db:seed', ['--class' => 'MenuBuilderSeeder']);

            $this->deleteImageDirectories();

            return response()->json([
                'status' => 'success',
                'message' => 'Database wiped successfully'
            ]);
        } catch (\Exception $e) {
            logger("Failed to clear database: {$e->getMessage()}");

            return response()->json([
                'status' => 'error',
                'message' => 'something went wrong!'
            ], 500);
        }
    }

    private function deleteImageDirectories(): void
    {
        $path = public_path('uploads');
        $preserveFolders = ['payment-gateway'];

        if (!File::isDirectory($path)) {
            return;
        }

        $directories = File::directories($path);

        foreach ($directories as $dirPath) {
            $folderName = basename($dirPath);

            if (in_array($folderName, $preserveFolders)) {
                continue;
            }

            File::deleteDirectory($dirPath);
        }
    }
}