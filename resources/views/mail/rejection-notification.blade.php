<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="border: 1px solid #e0e0e0; border-radius: 5px; overflow: hidden;">
        <div style="background-color: #0056b3; padding: 20px; text-align: center;">
            <img src="https://ro11.ched.gov.ph/wp-content/uploads/2022/02/cropped-ched_logo.png" alt="CHED Logo" style="max-width: 150px;">
        </div>
        
        <div style="padding: 30px; background-color: #ffffff;">
            <h1 style="color: #d9534f; margin-top: 0;">Account Registration Unsuccessful</h1>
            
            <p>Dear {{ $name }},</p>
            
            <p>We regret to inform you that your registration for a ProgMES (Program Monitoring and Evaluation System) account has been unsuccessful.</p>
            
            @if(isset($reason) && $reason)
            <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; padding: 15px; margin: 20px 0; color: #721c24;">
                <p><strong>Reason for rejection:</strong> {{ $reason }}</p>
            </div>
            @endif
            
            <p>The email address you provided ({{ $email }}) could not be registered at this time.</p>
            
            <p>If you believe this is an error or would like to provide additional information, please contact us at <a href="mailto:chedro11@ched.gov.ph" style="color: #0056b3;">chedro11@ched.gov.ph</a>.</p>
            
            <div style="border-top: 1px solid #eee; margin: 20px 0;"></div>
        </div>
        
        <div style="background-color: #f5f5f5; padding: 15px; text-align: center; font-size: 12px; color: #666;">
            <p>This is an automated message from CHED Regional Office XI.</p>
            <p>&copy; {{ date('Y') }} Commission on Higher Education - Regional Office XI. All rights reserved.</p>
            <p>Address: University of Southeastern Philippines Compound, Loyola St., Bo. Obrero, Davao City</p>
        </div>
    </div>
</div>
