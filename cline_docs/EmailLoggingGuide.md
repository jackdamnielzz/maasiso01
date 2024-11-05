# Email Sending Logging Guide

## Logging Mechanism

The contact form now uses an advanced logging system to track email sending attempts, errors, and system configurations.

### Log Locations
- Primary Log: `contact-form-debug.log`
- Potential Locations:
  - `/var/log/contact-form-debug.log`
  - Project root directory
  - System temporary directory

### Log Levels
- DEBUG: Detailed technical information
- INFO: General successful operations
- WARNING: Potential issues
- ERROR: Failed operations
- CRITICAL: Severe system problems

### What to Check
1. Verify log file exists
2. Review log contents for:
   - Email sending attempts
   - System configuration details
   - Specific error messages

### Troubleshooting Steps
1. Open `contact-form-debug.log`
2. Look for:
   - Failed email sending attempts
   - System configuration issues
   - Specific error messages

### Common Issues to Investigate
- PHP mail() function configuration
- SMTP settings
- Server email permissions
- Firewall restrictions

## Example Log Entry
```
[2024-11-05 10:30:45] [INFO] Attempting to send email to: info@maasiso.nl
[2024-11-05 10:30:45] [DEBUG] Email Subject: New Contact Form Submission
[2024-11-05 10:30:45] [ERROR] Email sending failed
```

## Next Steps
1. Check log file
2. Analyze error messages
3. Consult hosting provider if persistent issues occur
