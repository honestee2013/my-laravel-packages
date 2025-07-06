<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee ID Card - {{ $employee->user->name ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            flex-direction: column; /* Add this line */
        }

        .id-card-container {
            width: 350px; /* Standard ID card width */
            height: 550px; /* Standard ID card height, adjust as needed */
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            text-align: center;
            position: relative;
            padding-bottom: 20px; /* Space for potential bottom info */
        }

        .header {
            background-color: #004d99; /* Company primary color */
            color: #ffffff;
            padding: 10px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%; /* If logo is round, otherwise remove */
            object-fit: contain; /* Ensures logo fits without distortion */
            background-color: #fff; /* Background for logo area */
            padding: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .company-name {
            font-size: 1.1em;
            font-weight: bold;
            margin-top: 5px;
            padding: 0.1em;
        }

        .photo-section {
            margin-top: 1em;
        }

        .employee-photo {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .info-section {
            padding: 0 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .label {
            font-size: 0.7em;
            color: #555;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            display: block;
        }

        .value {
            font-size: 1em;
            color: #333;
            font-weight: 600;
        }

        .qr-code-section {
            padding: 0px 0 25px 0; /* Adjust padding to center QR visually */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* The QR code itself is an SVG, so no specific styling for a placeholder box needed */
        .qr-code svg {
            width: 130px;
            height: 130px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }


        /* Optional: Footer or Disclaimer */
        .footer {
            font-size: 0.73em;
            color: #888;
            padding: 5px 20px;
            background-color: #f7f7f7;
            border-top: 1px solid #eee;
            position: absolute;
            bottom: 0;
            width: 100%;
            box-sizing: border-box;
        }






        .download-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745; /* Green color */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .download-button:hover {
            background-color: #218838;
        }



    </style>
</head>
<body>

    <div class="id-card-container">
        <div class="header">
            {{-- Replace with your actual company logo --}}
            <!--<img src="{{ asset('$employee->user->basicInfo->profile_picture') }}" alt="Company Logo" class="company-logo">-->
            <div class="company-name">Agriwatss Nigeria Ltd.</div>
        </div>

        <div class="photo-section">
            {{-- Dynamically load employee photo. Ensure your user/employee profile has a 'photo_url' attribute or similar --}}
            <img src="{{ asset('storage/' .$employee->user->basicInfo->profile_picture) ?? asset('assets/img/default_profile_picture.jpg') }}" alt="Employee Photo" class="employee-photo">
        </div>

        <div class="info-sectio" style="margin-top:0.5em; margin-bottom:1em">
            <div class="info-item">
                <span class="label">Full Name</span>
                <span class="value" id="employee-name">{{ $employee->user->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label">Employee ID</span>
                <span class="value" id="employee-id">{{ $employee->employee_id ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label">Job Title</span>
                <span class="value" id="job-title">{{ $employee->designation ?? 'N/A' }}</span>
            </div>

            {{-- You can add more fields from the EmployeeProfile or User table --}}
            {{--
            <div class="info-item">
                <span class="label">Email</span>
                <span class="value" id="employee-email">{{ $employee->user->email ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="label">Joining Date</span>
                <span class="value" id="joining-date">{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('M d, Y') : 'N/A' }}</span>
            </div>
            --}}
        </div>

        <div class="qr-code-section">
            <div class="qr-code">
                {!! $qrCodeSvg !!} {{-- Use {!! !!} for unescaped content like SVG --}}
            </div>
        </div>

        <div class="footer">
            If found, please return to Agriwatss Nigeria Ltd. HR Department.
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ route('download.employee.id.card', ['employeeprofile' => 1]) }}" class="download-button" >
            Download ID Card (PDF)
        </a>
    </div>

</body>
</html>
