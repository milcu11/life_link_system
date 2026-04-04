<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px;">
    <h2 style="color: #d32f2f; margin-bottom: 20px;">
        🩸 Urgent Blood Donation Request
    </h2>

    <p>Dear <strong>{{ $donor->user->name }}</strong>,</p>

    <p>A <strong>{{ strtoupper($request->urgency_level) }}</strong> priority blood donation request matching your blood type has been received!</p>

    <!-- Request Details Card -->
    <div style="background-color: #f5f5f5; padding: 15px; border-left: 4px solid #d32f2f; margin: 20px 0;">
        <h3 style="color: #d32f2f; margin-top: 0;">Request Details</h3>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; font-weight: bold; width: 40%;">Blood Type Needed:</td>
                <td style="padding: 8px;"><strong style="color: #d32f2f; font-size: 18px;">{{ $request->blood_type }}</strong></td>
            </tr>
            <tr style="background-color: #fff;">
                <td style="padding: 8px; font-weight: bold;">Quantity Needed:</td>
                <td style="padding: 8px;">{{ $request->quantity }} units</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Urgency Level:</td>
                <td style="padding: 8px;">
                    <span style="background-color: 
                        @if($request->urgency_level === 'critical') #d32f2f
                        @elseif($request->urgency_level === 'high') #ff6f00
                        @else #fbc02d
                        @endif; 
                        color: white; padding: 4px 8px; border-radius: 3px; font-weight: bold;">
                        {{ strtoupper($request->urgency_level) }}
                    </span>
                </td>
            </tr>
            <tr style="background-color: #fff;">
                <td style="padding: 8px; font-weight: bold;">Hospital:</td>
                <td style="padding: 8px;">{{ $hospital->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Patient Name:</td>
                <td style="padding: 8px;">{{ $request->patient_name }}</td>
            </tr>
            <tr style="background-color: #fff;">
                <td style="padding: 8px; font-weight: bold;">Location:</td>
                <td style="padding: 8px;">{{ $request->location }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; font-weight: bold;">Compatibility Score:</td>
                <td style="padding: 8px;">
                    <strong style="color: #4caf50;">{{ $match->compatibility_score }}%</strong> match
                </td>
            </tr>
            @if($match->distance)
            <tr style="background-color: #fff;">
                <td style="padding: 8px; font-weight: bold;">Distance from You:</td>
                <td style="padding: 8px;">{{ $match->distance }} km</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Needed By -->
    @if($request->needed_by)
    <div style="background-color: #fff3cd; padding: 15px; border-left: 4px solid #ff6f00; margin: 20px 0;">
        <strong>⏰ Needed by:</strong> {{ $request->needed_by->format('M d, Y H:i') }}
    </div>
    @endif

    <!-- Contact Information -->
    <div style="background-color: #e3f2fd; padding: 15px; border-radius: 4px; margin: 20px 0;">
        <h4 style="color: #1976d2; margin-top: 0;">Contact Information</h4>
        <p style="margin: 8px 0;">
            <strong>Contact Person:</strong> {{ $request->contact_person }}<br>
            <strong>Phone:</strong> <a href="tel:{{ $request->contact_phone }}" style="color: #1976d2; text-decoration: none;">{{ $request->contact_phone }}</a>
        </p>
        @if($request->notes)
        <p style="margin: 8px 0;">
            <strong>Additional Notes:</strong><br>
            {{ $request->notes }}
        </p>
        @endif
    </div>

    <!-- Call to Action -->
    <div style="margin: 30px 0; text-align: center;">
        <a href="{{ route('donor.requests') }}" style="display: inline-block; background-color: #d32f2f; color: white; padding: 12px 30px; text-decoration: none; border-radius: 4px; font-weight: bold; font-size: 16px;">
            View Request & Respond
        </a>
    </div>

    <!-- Important Notes -->
    <div style="background-color: #f5f5f5; padding: 15px; margin: 20px 0; border-radius: 4px; font-size: 14px;">
        <h4 style="color: #666; margin-top: 0;">Important Information</h4>
        <ul style="margin: 8px 0; padding-left: 20px;">
            <li>Please respond to this request as soon as possible if you are able to donate.</li>
            <li>Ensure you meet all health requirements before donating.</li>
            <li>If you have any health concerns or restrictions, please inform the hospital staff.</li>
            <li>This is a time-sensitive request. Your quick action could save a life.</li>
        </ul>
    </div>

    <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
        Thank you for being part of the LifeLink blood donation network and helping save lives!<br>
        <strong>LifeLink - Blood Donation Management System</strong>
    </p>
</div>
