<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px;">
    <h2 style="color: #4caf50; margin-bottom: 20px;">
        ✅ Donor Accepted Your Blood Request
    </h2>

    <p>Dear <strong>{{ $hospital->name }}</strong>,</p>

    <p>Great news! A donor has accepted your blood request. Here are the details:</p>

    <!-- Donor Information Card -->
    <div style="background-color: #e8f5e9; padding: 15px; border-left: 4px solid #4caf50; margin: 20px 0;">
        <h3 style="color: #2e7d32; margin-top: 0;">Donor Information</h3>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; font-weight: bold; width: 40%;">Donor Name:</td>
                <td style="padding: 8px;">{{ $donor->user->name }}</td>
            </tr>
            <tr style="background-color: #f5f5f5;">
                <td style="padding: 8px; font-weight: bold;">Blood Type:</td>
                <td style="padding: 8px;"><strong style="color: #d32f2f; font-size: 16px;">{{ $donor->blood_type }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Email:</td>
                <td style="padding: 8px;"><a href="mailto:{{ $donor->user->email }}" style="color: #1976d2; text-decoration: none;">{{ $donor->user->email }}</a></td>
            </tr>
            <tr style="background-color: #f5f5f5;">
                <td style="padding: 8px; font-weight: bold;">Phone:</td>
                <td style="padding: 8px;"><a href="tel:{{ $donor->phone }}" style="color: #1976d2; text-decoration: none;">{{ $donor->phone }}</a></td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Address:</td>
                <td style="padding: 8px;">{{ $donor->address }}</td>
            </tr>
            <tr style="background-color: #f5f5f5;">
                <td style="padding: 8px; font-weight: bold;">Gender:</td>
                <td style="padding: 8px;">{{ ucfirst($donor->gender) }}</td>
            </tr>
            @if($donor->medical_conditions)
            <tr>
                <td style="padding: 8px; font-weight: bold;">Medical Conditions:</td>
                <td style="padding: 8px;">{{ $donor->medical_conditions }}</td>
            </tr>
            @endif
            @if($donor->emergency_contact)
            <tr style="background-color: #f5f5f5;">
                <td style="padding: 8px; font-weight: bold;">Emergency Contact:</td>
                <td style="padding: 8px;">{{ $donor->emergency_contact }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Request Details -->
    <div style="background-color: #f5f5f5; padding: 15px; border-left: 4px solid #1976d2; margin: 20px 0;">
        <h3 style="color: #1976d2; margin-top: 0;">Request Details</h3>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; font-weight: bold; width: 40%;">Patient Name:</td>
                <td style="padding: 8px;">{{ $request->patient_name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Blood Type Needed:</td>
                <td style="padding: 8px;"><strong style="color: #d32f2f;">{{ $request->blood_type }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Location:</td>
                <td style="padding: 8px;">{{ $request->location }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Compatibility Score:</td>
                <td style="padding: 8px;"><strong style="color: #4caf50;">{{ $match->compatibility_score }}%</strong></td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Distance:</td>
                <td style="padding: 8px;">{{ $match->distance }} km away</td>
            </tr>
        </table>
    </div>

    <!-- Next Steps -->
    <div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ff6f00; margin: 20px 0;">
        <h4 style="color: #e65100; margin-top: 0;">Next Steps</h4>
        <ul style="margin: 8px 0; padding-left: 20px;">
            <li>Contact the donor to arrange the donation appointment</li>
            <li>Verify the donor's health status before donation</li>
            <li>Once donation is completed, update the request status</li>
            <li>Conduct any necessary post-donation procedures</li>
        </ul>
    </div>

    <!-- Action Button -->
    <div style="margin: 30px 0; text-align: center;">
        <a href="{{ route('hospital.requests.show', $request) }}" style="display: inline-block; background-color: #4caf50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 16px;">
            View Request Details
        </a>
    </div>

    <!-- Important Notes -->
    <div style="background-color: #f5f5f5; padding: 15px; margin: 20px 0; border-radius: 4px; font-size: 14px;">
        <h4 style="color: #666; margin-top: 0;">Important Information</h4>
        <ul style="margin: 8px 0; padding-left: 20px;">
            <li>Ensure you verify the donor's health and eligibility before proceeding</li>
            <li>Keep all donor information confidential</li>
            <li>Follow all health and safety protocols</li>
            <li>Document the donation process properly</li>
        </ul>
    </div>

    <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
        This is an automated notification from LifeLink.<br>
        <strong>LifeLink - Blood Donation Management System</strong>
    </p>
</div>
