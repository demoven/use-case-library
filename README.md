# use-case-library

The Use Case Library is a WordPress plugin designed to manage and display a library of use cases. This plugin enables administrators to input, categorize, and display use cases in a user-friendly interface, making it easy for visitors to browse and explore relevant examples.

## Features 

- **Use Case Management**: Edit, publish, unpublish and delete use cases directly from the WordPress admin dashboard.

- **Category Filters**: Allows users to filter use cases by category or other attributes.

- **Detailed View**: Click on a use case to view its detailed description in a new window.

- **Image Uploads**: Supports uploading and associating images with use cases.

- **Database Integration**: Connects to a MySQL database to store and retrieve use case data.

## Installation

1. Download the plugin ZIP file.

2. Log in to your WordPress admin dashboard.

3. Navigate to ```Plugins > Add New``` and click ```Upload Plugin```.

4. Select the ZIP file and click ```Install Now```.

5. Activate the plugin from the ```Installed Plugins``` page.

6. Create a page for the use case library and add the following shortcode: ```[display_use_cases]```.

7. Create a page for the form and add the following shortcode: ```[form-use-case]```.

8. Create a page for the use case details:
- **Title**: use case details
- **Slug**: use-case-details
- **Template**: Use Case Template