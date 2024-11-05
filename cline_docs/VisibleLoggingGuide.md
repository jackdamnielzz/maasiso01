# Visible Logging Guide for Contact Form

## Logging Mechanism

The contact form now uses a direct, visible logging approach:

### Log File Location
- `contact-form-log.txt` in the same directory as `contact-handler.php`
- Easily accessible and visible
- Captures all form submission attempts

### Log Levels
- INFO: General information
- DEBUG: Detailed technical details
- WARNING: Potential issues
- ERROR: Failed operations
- CRITICAL: Severe problems
- SUCCESS: Successful operations

### What to Look For
1. Open `contact-form-log.txt`
2. Check entries for:
   - Form submission attempts
   - Validation results
   - Email sending status

### Troubleshooting
- Verify log file exists
- Check file permissions
- Look for specific error messages

### Example Log Entry
```
[2024-11-05 10:30:45] [INFO] Received contact form submission
[2024-11-05 10:30:45] [DEBUG] POST Data: (sanitized data)
[2024-11-05 10:30:45] [SUCCESS] Email sent successfully
```

## Immediate Visibility
- Log file updates in real-time
- No complex configuration needed
- Easy to check directly on the server
