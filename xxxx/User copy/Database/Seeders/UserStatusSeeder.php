<?php

namespace App\Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
// use Illuminate\Support\Facades\Log; // Optional: for logging if a category is not found

class UserStatusSeeder extends Seeder
{
    public function run()
    {
        $categoryIds = DB::table('user_status_categories')
            ->pluck('id', 'name')
            ->toArray();
        $now = Carbon::now();

        $userAccountStatuses = [

            // === Registration & Initial Onboarding ===
            ['name' => 'new_registration', 'display_name' => 'New Registration', 'description' => 'Account created via registration form, awaiting initial actions like email verification or profile setup.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'invited', 'display_name' => 'Invited', 'description' => 'User has been invited to the platform but has not yet accepted or registered.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'pending_first_login', 'display_name' => 'Pending First Login', 'description' => 'Account created (e.g. by admin or import) but user has not logged in for the first time.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'awaiting_profile_completion', 'display_name' => 'Awaiting Profile Completion', 'description' => 'User registered, but essential profile information is incomplete.',  'category_id' => $categoryIds['user_profile_status'] ?? $categoryIds['account_status'] ?? null],
            ['name' => 'profile_setup_incomplete_critical', 'display_name' => 'Profile Setup Incomplete (Critical)', 'description' => 'User must complete critical profile information before full access is granted.',  'category_id' => $categoryIds['user_profile_status'] ?? $categoryIds['account_status'] ?? null],
            ['name' => 'onboarding_pending', 'display_name' => 'Onboarding Pending', 'description' => 'User needs to complete specific onboarding steps/checklist.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'onboarding_complete', 'display_name' => 'Onboarding Complete', 'description' => 'User has successfully completed all initial onboarding steps.',  'category_id' => $categoryIds['account_status'] ?? null],

            // === Verification States ===
            ['name' => 'email_unverified', 'display_name' => 'Email Unverified', 'description' => 'User\'s primary email address has not been verified.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'email_verification_sent', 'display_name' => 'Email Verification Sent', 'description' => 'A verification link/code has been sent to the user\'s email.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'email_verified', 'display_name' => 'Email Verified', 'description' => 'User\'s primary email address has been successfully verified.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'pending_email_change_verification', 'display_name' => 'Pending Email Change Verification', 'description' => 'User requested an email change, new email requires verification.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'phone_unverified', 'display_name' => 'Phone Unverified', 'description' => 'User\'s phone number has not been verified.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'phone_verification_pending', 'display_name' => 'Phone Verification Pending', 'description' => 'OTP sent or call initiated for phone verification.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'phone_verified', 'display_name' => 'Phone Verified', 'description' => 'User\'s phone number has been successfully verified.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'pending_phone_change_verification', 'display_name' => 'Pending Phone Change Verification', 'description' => 'User requested a phone number change, new number requires verification.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'identity_documents_pending', 'display_name' => 'Identity Docs Pending Submission', 'description' => 'User is required to submit identity verification documents (KYC).',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'identity_documents_submitted', 'display_name' => 'Identity Docs Submitted', 'description' => 'User has submitted identity documents, awaiting review.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'identity_verification_in_review', 'display_name' => 'Identity Verification In Review', 'description' => 'Submitted identity documents are currently being reviewed (KYC).',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'identity_verified', 'display_name' => 'Identity Verified', 'description' => 'User identity has been successfully verified (KYC passed).',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'identity_verification_rejected', 'display_name' => 'Identity Verification Rejected', 'description' => 'User identity verification (KYC) was rejected. Reason may be provided.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'identity_verification_requires_resubmission', 'display_name' => 'Identity Resubmission Required', 'description' => 'User needs to resubmit identity documents due to issues with previous submission.',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'partially_verified', 'display_name' => 'Partially Verified', 'description' => 'Some, but not all, required verification steps completed (e.g., email verified, but identity pending).',  'category_id' => $categoryIds['user_verification_status'] ?? null],
            ['name' => 'fully_verified', 'display_name' => 'Fully Verified', 'description' => 'All required verifications (email, phone, identity, etc.) completed.',  'category_id' => $categoryIds['user_verification_status'] ?? null],

            // === Account Activation & Lifecycle States ===
            ['name' => 'pending_admin_activation', 'display_name' => 'Pending Admin Activation', 'description' => 'Account created but requires manual activation by an administrator.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'active', 'display_name' => 'Active', 'description' => 'User account is active, verified (if required), and can access platform features.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'inactive_by_user', 'display_name' => 'Inactive (By User)', 'description' => 'User has voluntarily chosen to deactivate their account temporarily.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'inactive_due_to_inactivity', 'display_name' => 'Inactive (System)', 'description' => 'Account automatically marked as inactive due to prolonged period of no activity.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'dormant', 'display_name' => 'Dormant', 'description' => 'Account has been inactive for an extended period, potentially subject to specific data retention or reactivation policies.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'grace_period', 'display_name' => 'Grace Period', 'description' => 'Account is in a grace period (e.g., after subscription expiry) before full deactivation or feature limitation.',  'category_id' => $categoryIds['account_status'] ?? null],


            // === Security Related States ===
            ['name' => 'password_reset_initiated', 'display_name' => 'Password Reset Initiated', 'description' => 'User has requested a password reset; awaiting confirmation via link or code.',  'category_id' => $categoryIds['user_security_status'] ?? null],
            ['name' => 'password_expired', 'display_name' => 'Password Expired', 'description' => 'User\'s password has expired based on security policy and must be changed upon next login.',  'category_id' => $categoryIds['user_security_status'] ?? null],
            ['name' => 'force_password_change', 'display_name' => 'Force Password Change', 'description' => 'User is required to change their password on next login (e.g. due to admin action or security breach).',  'category_id' => $categoryIds['user_security_status'] ?? null],
            ['name' => '2fa_pending_setup', 'display_name' => '2FA Pending Setup', 'description' => 'Two-factor authentication is required or enabled but not yet configured by the user.',  'category_id' => $categoryIds['user_security_status'] ?? null],
            ['name' => '2fa_enabled', 'display_name' => '2FA Enabled', 'description' => 'Two-factor authentication is successfully configured and active for the account.',  'category_id' => $categoryIds['user_security_status'] ?? null],
            ['name' => 'locked_failed_logins', 'display_name' => 'Locked (Failed Logins)', 'description' => 'Account temporarily locked due to excessive failed login attempts.',  'category_id' => $categoryIds['user_security_status'] ?? null],
            ['name' => 'locked_by_admin', 'display_name' => 'Locked (By Admin)', 'description' => 'Account manually locked by an administrator for security or other reasons.',  'category_id' => $categoryIds['user_security_status'] ?? null],
            ['name' => 'suspicious_activity_detected', 'display_name' => 'Suspicious Activity Detected', 'description' => 'Potential suspicious activity detected on the account, may require user action or admin review.',  'category_id' => $categoryIds['user_security_status'] ?? null],


            // === Administrative Actions, Sanctions & Restrictions ===
            ['name' => 'under_admin_review', 'display_name' => 'Under Admin Review', 'description' => 'Account flagged for administrative review due to policy violation, report, or unusual activity.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'deactivated_by_admin', 'display_name' => 'Deactivated (By Admin)', 'description' => 'Account manually deactivated by an administrator, often with a reason.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'suspended', 'display_name' => 'Suspended', 'description' => 'Account temporarily suspended due to policy violations. Access is restricted for a defined period or until resolved.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'banned', 'display_name' => 'Banned', 'description' => 'Account permanently banned from the platform due to severe or repeated violations.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'shadow_banned', 'display_name' => 'Shadow Banned', 'description' => 'User\'s content or activity is hidden from others or deprioritized without their explicit knowledge.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'limited_access', 'display_name' => 'Limited Access', 'description' => 'User account has restricted access to certain features or functionalities due to specific conditions.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'appeal_pending', 'display_name' => 'Appeal Pending', 'description' => 'User has appealed a sanction (e.g., suspension, ban) and is awaiting review.',  'category_id' => $categoryIds['account_status'] ?? null],

            // === Data Management, Deletion & Special Account States ===
            ['name' => 'pending_deletion_by_user', 'display_name' => 'Pending Deletion (User Request)', 'description' => 'User has requested account deletion. Account scheduled for permanent removal after a grace period.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'pending_deletion_by_admin', 'display_name' => 'Pending Deletion (Admin Request)', 'description' => 'Admin has marked account for deletion. Account scheduled for permanent removal.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'soft_deleted', 'display_name' => 'Soft Deleted', 'description' => 'Account is marked as deleted but data is retained for a period for potential recovery or compliance.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'permanently_deleted', 'display_name' => 'Permanently Deleted', 'description' => 'Account and associated personal data have been permanently removed or anonymized.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'archived', 'display_name' => 'Archived', 'description' => 'Account data is archived for long-term record-keeping but the account is inactive and not accessible.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'merged_into_another_account', 'display_name' => 'Merged', 'description' => 'This account has been merged into another user account; it is no longer active.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'test_account', 'display_name' => 'Test Account', 'description' => 'Account explicitly designated for testing purposes, may have different rules or data.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'guest_account', 'display_name' => 'Guest Account', 'description' => 'Temporary guest user with limited access, typically not requiring full registration or data persistence.',  'category_id' => $categoryIds['account_status'] ?? null],
            ['name' => 'impersonated', 'display_name' => 'Being Impersonated', 'description' => 'An administrator is currently logged in as this user for support or testing purposes.',  'category_id' => $categoryIds['user_security_status'] ?? null],
            ['name' => 'migrated_from_old_system', 'display_name' => 'Migrated', 'description' => 'Account was migrated from a previous system, may require initial actions by user (e.g., password reset, profile update).',  'category_id' => $categoryIds['account_status'] ?? null],

            // === Compliance & Consent States ===
            ['name' => 'terms_pending_acceptance', 'display_name' => 'Terms Pending Acceptance', 'description' => 'User must accept updated terms and conditions before proceeding.',  'category_id' => $categoryIds['user_compliance_status'] ?? $categoryIds['account_status'] ?? null],
            ['name' => 'terms_accepted', 'display_name' => 'Terms Accepted', 'description' => 'User has accepted the current terms and conditions.',  'category_id' => $categoryIds['user_compliance_status'] ?? null],
            ['name' => 'privacy_consent_pending', 'display_name' => 'Privacy Consent Pending', 'description' => 'User must provide or update consent for data processing under privacy policy.',  'category_id' => $categoryIds['user_compliance_status'] ?? null],
            ['name' => 'privacy_consent_given', 'display_name' => 'Privacy Consent Given', 'description' => 'User has provided necessary consents for data processing.',  'category_id' => $categoryIds['user_compliance_status'] ?? null],
            ['name' => 'privacy_consent_revoked', 'display_name' => 'Privacy Consent Revoked', 'description' => 'User has revoked previously given consent for data processing; account may be limited or pending deletion.',  'category_id' => $categoryIds['user_compliance_status'] ?? null],

        ];

        foreach ($userAccountStatuses as $status) {
            // Ensure all required fields are present before merging, especially 'editable'
            $statusData = array_merge(
                ['editable' => "No"], // Default editable to false
                $status,              // Current status data
                [                     // Timestamps
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );


            if (is_null($statusData['category_id'])) {
                // Consider logging this warning if a category wasn't found
                // Log::warning("User status category not found for status: {$statusData['name']}. Category ID set to null. Ensure categories like 'account_status', 'user_verification_status', etc., exist.");
            }

            DB::table('user_statuses')->updateOrInsert(
                ['name' => $statusData['name'], 'category_id' => $statusData['category_id']],
                $statusData
            );
        }
    }
}