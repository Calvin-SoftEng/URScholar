<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QrCodeService;

class QrCodeController extends Controller
{
    // protected $qrCodeService;

    // public function __construct(QrCodeService $qrCodeService)
    // {
    //     $this->qrCodeService = $qrCodeService;
    // }

    // public function show()
    // {
    //     $user = auth()->user(); // Get the logged-in user
    //     $qrCode = $this->qrCodeService->generateQrCode($user->name);

    //     return response($qrCode)->header('Content-Type', 'image/png');
    // }

    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function download()
    {
        $user = auth()->user();
        $qrCode = $this->qrCodeService->generateQrCode($user->name);

        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="QRCode.png"');
    }
}
