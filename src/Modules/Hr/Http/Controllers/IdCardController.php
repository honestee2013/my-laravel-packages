<?php

namespace App\Modules\Hr\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Hr\Models\EmployeeProfile; // Make sure to import your EmployeeProfile model
use SimpleSoftwareIO\QrCode\Facades\QrCode; // We'll add this later for QR code generation
use Illuminate\Routing\Controller;


use Barryvdh\DomPDF\Facade\Pdf; // Import the PDF facade



class IdCardController extends Controller
{
    /**
     * Display the ID card for the authenticated user.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showMyIdCard()
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Load the employee profile with the related user data
        $employee = EmployeeProfile::with('user')
                                    ->where('user_id', $userId)
                                    ->first();

        // If no employee profile is found for the authenticated user
        if (!$employee) {
            return redirect()->back()->with('error', 'Your employee profile could not be found.');
        }

        // --- QR Code Generation Logic ---
        // For dynamic QR codes, you might encode something like:
        // - The employee's unique ID from employee_profiles table ($employee->employee_id or $employee->id)
        // - A timestamp for dynamic/expiring QR codes (e.g., for attendance)
        // - A unique token generated per request
        //
        // Example for a basic QR code (you'll need to install the QR code package first):
        // composer require simplesoftwareio/simple-qrcode ~4
        //
        // You would typically generate the QR code as an SVG or PNG data URL.
        $qrCodeData = json_encode([
            'employee_id' => $employee->employee_id,
            //'user_id' => $employee->user_id,
            //'timestamp' => now()->timestamp, // Good for dynamic/expiring codes
            // 'token' => uniqid(), // For even more security, generate a unique token and store it
        ]);

        // Generate QR code as SVG. You can choose PNG or other formats.
        $qrCodeSvg = QrCode::size(140)->generate($qrCodeData);
        // Note: For production, you might want to cache this or generate it on demand.
        // For physical cards, generate once and store. For digital, dynamically generate.

        return view('hr.views::id_card.show', compact('employee', 'qrCodeSvg'));

    }

    /**
     * Display the ID card for a specific employee (for HR manager access).
     *
     * @param  int  $userId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showEmployeeIdCard($userId)
    {
        // Add authorization check here to ensure only HR managers can access this
        // For example, using Laravel Gates or Policies:
        // $this->authorize('view', EmployeeProfile::class); // Or a specific policy like 'viewAnyIdCard'

        $employee = EmployeeProfile::with('user')
                                    ->where('user_id', $userId)
                                    ->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee profile not found.');
        }

        // --- QR Code Generation Logic (similar to above) ---
        $qrCodeData = json_encode([
            'employee_id' => $employee->employee_id,
            //'user_id' => $employee->user_id,
            //'timestamp' => now()->timestamp,
        ]);
        $qrCodeSvg = QrCode::size(140)->generate($qrCodeData);


        return view('hr.views::id_card.show', compact('employee', 'qrCodeSvg'));
    }












/**
     * Download the ID card as a PDF for the authenticated user.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadMyIdCard()
    {


        $userId = Auth::id();
        $employee = EmployeeProfile::with('user')
                                    ->where('user_id', $userId)
                                    ->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Your employee profile could not be found for download.');
        }

      // --- Fallback: Generate QR Code as SVG, then Base64 Encode it ---
        $qrCodeData = json_encode([
            'employee_id' => $employee->employee_id,
            //'user_id' => $employee->user_id,
            //'timestamp' => now()->timestamp,
        ]);

        // 1. Generate QR code as SVG (this doesn't require Imagick)
        $qrCodeSvgString = QrCode::size(140)->margin(1)->generate($qrCodeData); // Default format is SVG

        // 2. Base64 encode the SVG string
        $qrCodeBase64 = base64_encode($qrCodeSvgString);

        // 3. Create the data URI for embedding in an <img> tag
        $qrCodeImageSrc = 'data:image/svg+xml;base64,' . $qrCodeBase64;


        // Render the blade view into HTML
        $pdf = Pdf::loadView('hr.views::id_card.show_printable', compact('employee', 'qrCodeImageSrc'));

        // You might want to set paper size and orientation specific for ID cards.
        // ID card size is usually CR80 (85.60 × 53.98 mm). DomPDF works better with points.
        // 1mm = 2.83465 points
        // 85.60mm = 242.34 points
        // 53.98mm = 152.92 points
        // You might need to adjust CSS in show_printable.blade.php for these exact dimensions.
        // For simplicity, let's keep it letter/portrait and adjust the inner card div in CSS.
        // $pdf->setPaper([0, 0, 242.34, 152.92], 'landscape'); // Custom size for CR80 if entire PDF is just the card

        // Return the PDF for download
        return $pdf->download('ID_Card_' . ($employee->user->name ?? 'unknown') . '.pdf');


    }

    /**
     * Download the ID card as a PDF for a specific employee (for HR manager access).
     *
     * @param  int  $userId
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadEmployeeIdCard($userId)
    {
        // Add authorization check here
        // $this->authorize('download', EmployeeProfile::class); // Or a specific policy like 'downloadAnyIdCard'

        $employee = EmployeeProfile::with('user')
                                    ->where('user_id', $userId)
                                    ->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee profile not found for download.');
        }

        // Generate QR Code SVG (same logic as above)
        $qrCodeData = json_encode([
            'employee_id' => $employee->employee_id,
            //'user_id' => $employee->user_id,
            //'timestamp' => now()->timestamp,
        ]);
        $qrCodeSvg = QrCode::size(140)->generate($qrCodeData);

        // Render the blade view into HTML
        $pdf = Pdf::loadView('hr.views::id_card.show_printable', compact('employee', 'qrCodeSvg'));

        return $pdf->download('ID_Card_' . ($employee->user->name ?? 'unknown') . '.pdf');
    }









}
