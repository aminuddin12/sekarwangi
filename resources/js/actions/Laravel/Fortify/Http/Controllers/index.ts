import PasswordResetLinkController from './PasswordResetLinkController'
import NewPasswordController from './NewPasswordController'
import TwoFactorAuthenticatedSessionController from './TwoFactorAuthenticatedSessionController'
import EmailVerificationPromptController from './EmailVerificationPromptController'
import VerifyEmailController from './VerifyEmailController'
import EmailVerificationNotificationController from './EmailVerificationNotificationController'
import ConfirmablePasswordController from './ConfirmablePasswordController'
import ConfirmedPasswordStatusController from './ConfirmedPasswordStatusController'
import TwoFactorAuthenticationController from './TwoFactorAuthenticationController'
import TwoFactorQrCodeController from './TwoFactorQrCodeController'
import TwoFactorSecretKeyController from './TwoFactorSecretKeyController'
import RecoveryCodeController from './RecoveryCodeController'
import ConfirmedTwoFactorAuthenticationController from './ConfirmedTwoFactorAuthenticationController'

const Controllers = {
    PasswordResetLinkController: Object.assign(PasswordResetLinkController, PasswordResetLinkController),
    NewPasswordController: Object.assign(NewPasswordController, NewPasswordController),
    TwoFactorAuthenticatedSessionController: Object.assign(TwoFactorAuthenticatedSessionController, TwoFactorAuthenticatedSessionController),
    EmailVerificationPromptController: Object.assign(EmailVerificationPromptController, EmailVerificationPromptController),
    VerifyEmailController: Object.assign(VerifyEmailController, VerifyEmailController),
    EmailVerificationNotificationController: Object.assign(EmailVerificationNotificationController, EmailVerificationNotificationController),
    ConfirmablePasswordController: Object.assign(ConfirmablePasswordController, ConfirmablePasswordController),
    ConfirmedPasswordStatusController: Object.assign(ConfirmedPasswordStatusController, ConfirmedPasswordStatusController),
    TwoFactorAuthenticationController: Object.assign(TwoFactorAuthenticationController, TwoFactorAuthenticationController),
    TwoFactorQrCodeController: Object.assign(TwoFactorQrCodeController, TwoFactorQrCodeController),
    TwoFactorSecretKeyController: Object.assign(TwoFactorSecretKeyController, TwoFactorSecretKeyController),
    RecoveryCodeController: Object.assign(RecoveryCodeController, RecoveryCodeController),
    ConfirmedTwoFactorAuthenticationController: Object.assign(ConfirmedTwoFactorAuthenticationController, ConfirmedTwoFactorAuthenticationController),
}

export default Controllers