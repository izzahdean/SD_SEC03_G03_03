![header](...\image\header.png)

# :scroll: MyKakaks Cleaning Service System (MCSS)
MyKakaks Cleaning Service System (MCSS) is a web-based platform designed to offer 
professional house cleaning services in Kuala Lumpur. The system features an integrated
payment system and a streamlined booking process, enabling customers to conveniently
schedule and pay for cleaning services. It ensures customer trust and safety by including
background checks for all service providers.

## :clipboard: Table of Contents

- [Overview](#Overview)
- [Project Purpose and Scope](#ProjectPurposeandScope)
- [Project Objectives](#Objectives)
- [System Features](#SystemFeatures)
- [Installation and Setup](#InstallationandSetup)
- [Usage](#Usage)
- [Contributing](#Contributing)
- [License](#License)


## :mag: Project Purpose and Scope

**Purpose** \
The primary purpose of the MCSS is to create a convenient and user-friendly web platform 
for MyKakaks Cleaning Services to connect customers with reliable cleaning professionals, 
facilitating easy bookings and payments.

**Scope** 
- Geographic Coverage: Kuala Lumpur
- Main Features: 
    + Real-time service booking with flexible scheduling Integrated online payment system
    + Comprehensive cleaner profiles with background information for customer confidence
    + Four types of users: Guests, Customers, Staff, and Admins
	
## :round_pushpin: Project Objectives

    1. Develop a web-based system for MyKakaks Cleaning Services.
    2. Enhance customer trust through detailed ratings and cleaner profiles.
    3. Streamline booking and payment processes.


## :pushpin: System Features

- User Authentication : Secure login and registration for different user roles (guests, customers, staff, admins).
- Booking Module : Allows customers to schedule cleaning services and view available slots.
- Payment System : Integrated online payment for immediate service confirmation.
- Cleaner Profiles : Detailed profiles with background information and ratings for transparency.
- Admin Dashboard : Manage bookings, customer information, staff assignments, and system settings.

## :wrench: Installation and Setup

1. Clone the repository:

        # Visit https://git-scm.com to download and install console Git if not already installed

        # Clone the repository
        git clone https://github.com/izzahdean/SD_SEC03_G03_03.git

2. Ensure the following software is installed:
- [Github Desktop](https://desktop.github.com/download/)
- [UniServer](https://www.uniformserver.com/)
- [Notepad++](https://notepad-plus-plus.org/downloads/)
- Web Browser (e.g. Google Chrome or Microsoft Edge)

3. Once cloned, the project files will be available in a folder named after the repository in the directory you navigated to.

To access your system using UniServer, follow these steps after cloning your project:

4. Open UniServer
- Locate your UniServer installation directory.
- Double-click the UniController.exe file (usually found in the UniServer folder).

5. Start UniServer Modules
- In the UniServer Control Panel, click on Start Apache and Start MySQL to activate the web server and database server.

6. Place Your Project Files in the UniServer's www Directory
- Navigate to the UniServer directory.
- Open the www folder (typically located at UniServerZ/www).
- Place your cloned project folder inside the www directory.

7. Access the System via a Web Browser
- Open a web browser of your choice (e.g., Chrome, Firefox).
- Type http://localhost/<project-folder-name> into the address bar, replacing <project-folder-name> with the actual folder name of your project.
- Example: If the project folder is named MyKakaksProject, you would go to http://localhost/MyKakaksProject.

8. Configure the Database (For Admin)
+ Access phpMyAdmin through UniServer:
    - Go to http://localhost/phpmyadmin in your browser.
    - Create a database and import any necessary SQL files if applicable.

9. Test and Use the System
The system should now be accessible via the browser, allowing you to test and use the project as intended.

After successful installation and setup, accessing the system will display the guest homepage. This homepage 
features a clean and visually appealing design, showcasing the core service offerings of MyKakaks Cleaning 
Service. Users can explore available services, learn about professional cleaning options, and easily navigate 
to booking features using a prominent "Book Now" button for a seamless experience.
![homepage](...\image\custUI.jpg)


## :computer: Usage
1. Open the website on the configured local server.
2. Register or login as a customer, staff, or admin.
3. Navigate through the booking and payment modules to experience the full functionality.
4. Admins can manage system settings and view reports through the admin dashboard.


## :busts_in_silhouette: Contributing
Contributions are welcome! Please follow the standard process for pull requests and ensure compatibility with the existing project structure.	


## :scroll: License

This project is licensed under the MIT License. For more information, see the [LICENSE](https://github.com/izzahdean/SD_SEC03_G03_03/blob/main/LICENSE) file.

