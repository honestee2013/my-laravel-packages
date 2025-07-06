<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printable ID Card - {{ $employee->user->name ?? 'N/A' }}</title>
    <style>
        /* Define the exact dimensions for the ID card for printing */
        /* Standard CR80 ID Card dimensions: 85.6mm x 53.98mm (3.375 x 2.125 inches) */
        /* Convert to px for web view, or points for direct print output if DomPDF supports */
        /* For A4/Letter paper, position the card on it */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            /* For A4/Letter context, no centering needed directly on body */
        }

        .id-card-container {
            height: 3.375in; /* 85.6mm */
            width: 2.125in; /* 53.98mm */
            background-color: #ffffff;
            border-radius: 5px; /* Slight border-radius for printing */
            border: 1px solid #ccc; /* A light border to show card boundaries on a white page */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-align: center;
            position: relative;
            padding-bottom: 5px; /* Small padding at bottom */
            box-sizing: border-box; /* Include padding/border in width/height */

            /* Positioning on the A4/Letter page for printing */
            margin: 20px auto; /* Center on the page, or use fixed positioning */
        }

        .header {
            background-color: #004d99; /* Company primary color */
            color: #ffffff;
            padding: 8px 0; /* Reduced padding for compact ID card */
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px; /* Reduced gap */
        }

        .company-logo {
            width: 40px; /* Smaller logo for compact card */
            height: 40px;
            border-radius: 50%;
            object-fit: contain;
            background-color: #fff;
            padding: 2px;
        }

        .company-name {
            font-size: 0.7em; /* Smaller font for compact card */
            font-weight: bold;
            margin-top: 2px;
        }

        .photo-section {
            padding: 8px 0 5px 0; /* Reduced padding */
        }

        .employee-photo {
            width: 95px; /* Smaller photo for compact card */
            height: 95px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e0e0e0;
        }

        .info-section {
            padding: 0 10px; /* Reduced padding */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 3px; /* Reduced gap */
        }

        .info-item {
            margin-bottom: -1px; /* Reduced margin */
        }

        .label {
            font-size: 0.6em; /* Smaller font */
            color: #555;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            margin-bottom: 0px;
            display: block;
        }

        .value {
            font-size: 0.75em; /* Smaller font */
            color: #333;
            font-weight: 600;
        }

 /* Specific styling for the QR code image */
        .qr-code-section {
            padding: 5px 0 10px 0; /* Adjust padding as needed */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-code img { /* Target the img inside qr-code div */
            width: 70px; /* Adjust to desired display size on the card */
            height: 70px;
            border: 1px solid #ccc;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); /* Optional subtle shadow */
            object-fit: contain; /* Ensure the QR code fits perfectly */
            margin-top: 3px;
        }

        .footer {
            font-size: 0.5em; /* Smaller font */
            color: #888;
            padding: 3px 0px; /* Reduced padding */
            background-color: #f7f7f7;
            border-top: 1px solid #eee;
            position: absolute;
            bottom: 0;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>

    <div class="id-card-container">
        <div class="header">
            {{-- Replace with your actual company logo --}}
            <!--<img src="{{ asset('assets/img/default_profile_picture.jpg') }}" alt="Company Logo" class="company-logo">-->
            <div class="company-name">Agriwatss Nigeria Ltd.</div>
        </div>



        <div class="photo-section">
            <img src="{{ public_path('storage/' .$employee->user->basicInfo->profile_picture) ?? public_path('assets/img/default_profile_picture.jpg') }}" alt="Employee Photo" class="employee-photo">

        </div>

        <div class="info-section">
            <div class="info-item">

                <span class="value" id="employee-name">{{ $employee->user->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="value" id="employee-id">{{ $employee->employee_id ?? 'N/A' }}</span>
            </div>
            <div class="info-item">

                <span class="value" id="job-title">{{ $employee->designation ?? 'N/A' }}</span>
            </div>
            <div class="info-item">

                <span class="value" id="department">{{ $employee->department ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="qr-code-section">
            <div class="qr-code">
                <img src="{{ $qrCodeImageSrc }}" alt="QR Code">
            </div>
        </div>

        <div class="footer">
            If found, please return to Agriwatts Nigeria Ltd. HR Department.
        </div>
    </div>

</body>
</html>
