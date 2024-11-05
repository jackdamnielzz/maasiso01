# Email Configuration Guide for MaasISO Contact Form

## Plesk PHP Configuration Insights

### Key PHP Settings to Note
- PHP Version: 8.2.24
- Run as: FastCGI application
- Error Reporting: E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED
- Display Errors: On
- Log Errors: On

### Email Sending Configuration

#### Recommended Actions
1. Verify mail.force_extra_parameters setting
2. Check open_basedir restrictions
3. Ensure file_uploads is enabled

#### Potential Email Sending Improvements
- Use `-f` parameter to set sender email
- Implement robust error logging
- Add detailed error tracking

### Troubleshooting Checklist
- Confirm PHP mail() function is working
- Check server's mail configuration
- Verify email routing settings
- Test with different email clients

### Logging Locations
- PHP Errors: /var/log/php_errors.log
- Contact Form Errors: /var/log/contact-form-errors.log

### Advanced Configuration Options
```php
// Example of setting sender email
mail($to, $subject, $body, $headers, "-f info@maasiso.nl");
```

## Next Steps
1. Review server-side email configuration
2. Test email sending through PHP
3. Monitor error logs
4. Consider SMTP alternative if issues persist
