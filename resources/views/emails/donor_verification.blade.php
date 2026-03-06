@if($approved)
<p>Dear {{ $donor->user->name }},</p>

<p>Thank you for completing your donor registration. We have reviewed your submitted information and documents, and your account has been approved. You are now a verified donor on LifeLink.</p>

<p>Thank you for contributing to our community.</p>

<p>— The LifeLink Team</p>
@else
<p>Dear {{ $donor->user->name }},</p>

<p>Thank you for completing your donor registration. After reviewing your submitted information and documents, we were unable to approve your registration at this time.</p>

<p>If you believe this is an error or you'd like to provide more documentation, please contact support or update your profile and re-upload valid verification documents.</p>

<p>— The LifeLink Team</p>
@endif
