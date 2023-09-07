<?php
//include('api.php');

/*
Plugin Name:  Random Quotes Plugin
Plugin URI:   
Description:  Show a huge number of quotes from an API and from a list that has over 300 famous quotes. All of them from diferent and famous authors, it is shown in your posts and pages bottom.
Version:      1.0
Author:       Yeeyson Duarte
Author URI:   https://yeeysonduarte.vercel.app/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  Random Quotes Plugin
Domain Path:  /languages
*/

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// API Endpoint
define('Random_Quotes_Plugin_API_ENDPOINT', 'https://quotes15.p.rapidapi.com/quotes/random/');
// Secret keys
define('RAPIDAPI_HOST', 'quotes15.p.rapidapi.com');
define('RAPIDAPI_KEY', '9bec4868a9msh16a7c63eb3c566fp126084jsnfd40a828b922');


// Add the Quotes menu to the WordPress administrator panel
function Random_Quotes_Plugin_menu()
{
    add_menu_page(
        'Random Quotes',       // Menu title
        'Random Quotes',       // Page title
        'manage_options', // Capability required to access the menu
        'Random_Quotes_Plugin', // Menu slug
        'Random_Quotes_Plugin_settings_page', // Callback function to display the menu page
        'dashicons-format-quote' // Menu icon (optional)
    );
}
add_action('admin_menu', 'Random_Quotes_Plugin_menu');

// Callback function to display the Quotes settings page
function Random_Quotes_Plugin_settings_page()
{
?>
    <div class="wrap">
        <h1>Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('Random_Quotes_Plugin-settings-group'); ?>
            <?php do_settings_sections('Random_Quotes_Plugin'); ?>
            <?php submit_button(); ?>
        </form>
        <p>Thank you for downloading quotes plugin, this plugin shows a random quote at the bottom of all post and page.</p>
        <script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js" data-name="bmc-button" data-slug="extramedia19" data-color="#FFDD00" data-emoji="" data-font="Lato" data-text="Buy me a coffee" data-outline-color="#000000" data-font-color="#000000" data-coffee-color="#ffffff"></script>
    </div>
<?php
}

// Register the Quotes settings
function Random_Quotes_Plugin_register_settings()
{

    // Add a field for selecting the Quotes Source
    add_settings_field(
        'Random_Quotes_Plugin_source_field',      // Field ID
        'Select Quotes Source',     // Field title
        'Random_Quotes_Plugin_source_field_callback',    // Callback function to display the field
        'Random_Quotes_Plugin',            // Menu slug
        'Random_Quotes_Plugin_source_section'     // Section ID
    );

    // Add a section for Quotes Source
    add_settings_section(
        'Random_Quotes_Plugin_source_section',    // Section ID
        'Random Quotes Source',            // Section title
        'Random_Quotes_Plugin_source_section_callback',  // Callback function to display the section
        'Random_Quotes_Plugin'             // Menu slug
    );

    // Register the Quotes Source setting
    register_setting(
        'Random_Quotes_Plugin-settings-group',    // Settings group
        'Random_Quotes_Plugin_source'             // Setting name
    );
}
add_action('admin_init', 'Random_Quotes_Plugin_register_settings');

function Random_Quotes_Plugin_source_field_callback()
{
    echo esc_html('Select a source for the quotes, "API" has a limit of request a second and "List" is a list withing the plugin has over 300 quotes.') . "<br/>";
    $selected_source = get_option('Random_Quotes_Plugin_source'); // Get the currently selected source
    // Generate the options for the select field
    $options = array(
        'api' => 'API',
        'list' => 'List'
    );

    // Display the select field
    echo '<select name="Random_Quotes_Plugin_source">';
    foreach ($options as $value => $label) {
        $selected = ($selected_source === $value) ? 'selected' : '';
        echo '<option value="' . esc_attr($value) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    return $selected_source;
}

// Callback function to display the Quotes Source section
function Random_Quotes_Plugin_source_section_callback()
{
    $output = '';

    $output .= esc_html('Select a source for the quotes, "API" has a limit of request a second and "List" has over 300 quotes.');
    $selected_source = get_option('Random_Quotes_Plugin_source'); // Get the currently selected source
    // Generate the options for the select field
    $options = array(
        'api' => 'API',
        'list' => 'List'
    );

    // Build the select field
    $output .= '<select name="quotes_source">';
    foreach ($options as $value => $label) {
        $selected = ($selected_source === $value) ? 'selected' : '';
        $output .= '<option value="' . esc_attr($value) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
    }
    $output .= '</select>';

    return $selected_source;
}

// filter to add content to post
add_filter('the_content', 'Random_Quotes_Plugin_q435_insert_to_bottom');


function Random_Quotes_Plugin_q435_insert_to_bottom($content)
{
    $selectedValue = Random_Quotes_Plugin_source_section_callback();
    $quote = array();

    if ($selectedValue === 'api') {
        include('api.php');
        $data = json_decode($response);
        $quote = '<p style="color: #0a264f; font-size: 14px;font-weight: 600">'
            . $data->content .
            '<br> -' . $data->originator->name .
            '</p>';
    } elseif ($selectedValue === 'list') {

        $jsonString = file_get_contents(__DIR__ . '/data/data.json');
        $data = json_decode($jsonString, true); // Set the second argument to `false` if you want an object instead of an array


        // Get a random index within the range of the array length
        $randomIndex = array_rand($data);
        // Retrieve the random quote using the random index
        $randomQuote = $data[$randomIndex];


        $quote = ' <div><span style="color: #0a264f; font-size: 60px; position: absolute; margin-right:10px; margim-top:0px; margim-bottom:0px">"</span> </div>
            <p style="color: #0a264f; font-size: 14px;font-weight: 700;position: relative; padding-left: 70px; padding-top:15px">'
            . $randomQuote['text'] .
            '<br> - <span style="color: #0a264f; font-size: 11px; margin: 0px">' . $randomQuote['from'] . '</span></p>';
    }

    $content = $content . $quote;

    return $content;
}
