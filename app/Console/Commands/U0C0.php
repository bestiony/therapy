<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class U0C0 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'u0c0';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = storage_path('app/updates.zip');
        $demoPath = storage_path('app/updates');
        
        $response['success'] = 0;
        $response['message'] = 'File Not exist on storage';

        if(file_exists($path)){
            $zip = new ZipArchive;

            if(is_dir($demoPath)){
                File::deleteDirectory($demoPath);
            }
            else{
                File::makeDirectory($demoPath, 0777, true, true);
            }

            $res = $zip->open($path);

            if ($res === true) {
                try{
                    $res = $zip->extractTo($demoPath);
                    $versionFile = file_get_contents($demoPath.DIRECTORY_SEPARATOR.'update_note.txt');
                    $codeVersion = json_decode($versionFile)->build_version;
                    $currentVersion = getCustomerCurrentBuildVersion();
                    dump($codeVersion, $currentVersion);
                    $apiResponse = Http::acceptJson()->post('https://support.zainikthemes.com/api/745fca97c52e41daa70a99407edf44dd/glv', [
                        'app' => config('app.app_code')
                    ]);
                    
                    if($apiResponse->successful()){
                        $data = $apiResponse->object();
                        $latestVersion = $data->data->bv;
                        if($data->status === 'success'){
                            if($latestVersion == $codeVersion && $codeVersion > $currentVersion){
                                $res = $zip->extractTo(base_path());
                                $response['success'] = 1;
                                $response['message'] = 'Successfully done';
                                Log::info('Version '.$codeVersion.' update done');
                            }
                            else{
                                $response['message'] = 'Your code is not up to date';
                            }
                        }
                        else{
                            $response['message'] = $data->message;
                        }
                    }
                    else{
                        $data = $apiResponse->object();
                        $response['message'] = $data['message'];
                    }

                    File::deleteDirectory($demoPath);
                }
                catch(\Exception $e){
                    Log::info($e->getMessage());
                    $response['message'] = $e->getMessage();
                }
            }

            $zip->close();
        }
        print_r($response);
        return $response;
    }
}
