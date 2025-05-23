# Mobile Forensics Progress Tracker

A web application for tracking team progress during the Mobile Forensics hackathon project. This application allows teams to update and monitor their progress, as well as view other teams' progress.

## Team Members
- Ayush Kumar Barnwal (Leader)
- Yogesh Kumar
- Ashutosh Kumar
- Ayush Kumar

## Features

- Secure team member authentication
- Individual progress tracking
- Real-time progress updates
- Progress history
- Team comparison
- Responsive design for all devices
- Automatic updates every 30 seconds

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- XAMPP (for local development)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/YOUR_USERNAME/mobile-forensics-progress.git
cd mobile-forensics-progress
```

2. Create a MySQL database named `mobile_forensics_db`

3. Import the database structure:
   - Open phpMyAdmin
   - Select the `mobile_forensics_db` database
   - Go to the SQL tab
   - Copy and paste the contents of `database.sql`

4. Create `.env` file:
```bash
cp .env.example .env
```

5. Update the `.env` file with your database credentials and team keys:
```env
DB_HOST=localhost
DB_USER=your_database_user
DB_PASS=your_database_password
DB_NAME=mobile_forensics_db

# Team Member Access Keys
TEAM_MEMBER_1_KEY=ayush_kumar_barnwal_leader_2024_key
TEAM_MEMBER_2_KEY=yogesh_kumar_2024_key
TEAM_MEMBER_3_KEY=ashutosh_kumar_2024_key
TEAM_MEMBER_4_KEY=ayush_kumar_2024_key
```

6. Set up your web server to point to the project directory

7. Ensure the web server has write permissions for the project directory

## Usage

1. Access the application through your web browser
2. Log in using your team member access key
3. Update your progress using the form
4. View team progress in the dashboard

## Security

- The application uses secure session management
- Team member authentication is required for all operations
- Input validation and sanitization are implemented
- SQL injection prevention using prepared statements
- Environment variables for sensitive data

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository. 