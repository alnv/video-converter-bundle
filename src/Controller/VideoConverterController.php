<?php

namespace Alnv\VideoConverterBundle\Controller;

use Contao\FilesModel;
use Contao\Database;
use Contao\Dbafs;
use Contao\Config;
use Contao\StringUtil;
use Contao\CoreBundle\Controller\AbstractController;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\X264;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


#[Route(path: '/', name: 'video-converter-controller', defaults: ['_scope' => 'frontend', '_token_check' => false])]
class VideoConverterController extends AbstractController
{

    #[Route(path: '/converting-video/{id}/{index}', methods: ["POST", "GET"])]
    public function convertVideo($id, $index): JsonResponse
    {

        $this->container->get('contao.framework')->initialize();

        $arrReturn = [
            'ok' => true,
            'message' => ''
        ];

        $objSettings = Database::getInstance()->prepare('SELECT * FROM tl_video_converting WHERE id=?')->limit(1)->execute($id);
        $arrOutput = StringUtil::deserialize($objSettings->outputs, true)[$index] ?? [];

        if (empty($arrOutput)) {
            $arrReturn['ok'] = false;
            $arrReturn['message'] = 'no index found';
            return new JsonResponse($arrReturn);
        }

        $objVideoFile = FilesModel::findByUuid($objSettings->source);
        $objDestFolder = FilesModel::findByUuid($objSettings->dest);

        if (!$objDestFolder) {
            $arrReturn['ok'] = false;
            $arrReturn['message'] = 'no dest folder found';
            return new JsonResponse($arrReturn);
        }

        if (!$objVideoFile) {
            $arrReturn['ok'] = false;
            $arrReturn['message'] = 'no video found';
            return new JsonResponse($arrReturn);
        }
        try {

            $objFFMpeg = FFMpeg::create([
                'timeout' => 3600 * 12,
                'ffmpeg.binaries' => Config::get('ffmpegbinaries'),
                'ffprobe.binaries' => Config::get('ffprobebinaries'),
            ]);

            $strVideoName = $objVideoFile->path;
            $strVideoName = basename($strVideoName, ".mp4");
            $strVideoName = str_replace(" ", "_", $strVideoName);
            $strVideoName = preg_replace("/[^A-Za-z0-9\-\/_.]/", "", $strVideoName);

            if (!file_exists($objDestFolder->path . '/' . $strVideoName)) {
                mkdir(TL_ROOT . '/' . $objDestFolder->path . '/' . $strVideoName);
                Dbafs::addResource($objDestFolder->path . '/' . $strVideoName);
            }

            $strTargetFileName = $objDestFolder->path . '/' . $strVideoName . "/video_{$arrOutput['height']}p.mp4";

            $strThumbnail = $objDestFolder->path . '/' . $strVideoName . '/video_thumbnail.jpg';

            if (!file_exists(TL_ROOT . '/' . $strThumbnail)) {
                $this->thumbnail($strThumbnail, $objVideoFile->path);
            }

            $objVideo = $objFFMpeg->open(TL_ROOT . '/' . $objVideoFile->path);
            $objVideo->filters()->resize(new Dimension($arrOutput['width'], $arrOutput['height']))->synchronize();
            $objVideo->save(new X264(), TL_ROOT . '/' . $strTargetFileName);
        } catch (\Exception $objError) {
            $arrReturn['ok'] = false;
            $arrReturn['message'] = $objError->getMessage();
        }

        return new JsonResponse($arrReturn);
    }

    protected function thumbnail($strThumbnail, $strVideoPath): void
    {

        $objFFProbe = FFProbe::create([
            'ffmpeg.binaries' => Config::get('ffmpegbinaries'),
            'ffprobe.binaries' => Config::get('ffprobebinaries')
        ]);
        $objFFMpeg = FFMpeg::create([
            'ffmpeg.binaries' => Config::get('ffmpegbinaries'),
            'ffprobe.binaries' => Config::get('ffprobebinaries')
        ]);

        $intDuration = floor($objFFProbe->format(TL_ROOT . '/' . $strVideoPath)->get('duration'));
        $objVideo = $objFFMpeg->open(TL_ROOT . '/' . $strVideoPath);
        $objVideo->frame(TimeCode::fromSeconds(($intDuration >= 60 ? 60 : ($intDuration / 2))))->save(TL_ROOT . '/' . $strThumbnail);
    }
}