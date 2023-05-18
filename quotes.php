<?php
//include('api.php');

/*
Plugin Name:  Quotes
Plugin URI:   
Description:  Show a huge number of quotes from an API and from a list that has over 300 famous quotes. All of them from diferent and famous authors, it is to shown in your posts and pages bottom.
Version:      1.0
Author:       Yeeyson Duarte
Author URI:   https://yeeysonduarte.vercel.app/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  Quotes-tutorial
Domain Path:  /languages
*/

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// Add the Quotes menu to the WordPress administrator panel
function quotes_plugin_menu()
{
    add_menu_page(
        'Quotes',       // Menu title
        'Quotes',       // Page title
        'manage_options', // Capability required to access the menu
        'quotes-plugin', // Menu slug
        'quotes_plugin_settings_page', // Callback function to display the menu page
        'dashicons-format-quote' // Menu icon (optional)
    );
}
add_action('admin_menu', 'quotes_plugin_menu');

// Callback function to display the Quotes settings page
function quotes_plugin_settings_page()
{
?>
    <div class="wrap">
        <h1>Quotes Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('quotes-settings-group'); ?>
            <?php do_settings_sections('quotes-plugin'); ?>
            <?php submit_button(); ?>
        </form>
        <p>Thank you for downloading quotes plugin, this plugin shows a random quote at the bottom of all post and page.</p>
        <script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js" data-name="bmc-button" data-slug="extramedia19" data-color="#FFDD00" data-emoji="" data-font="Lato" data-text="Buy me a coffee" data-outline-color="#000000" data-font-color="#000000" data-coffee-color="#ffffff"></script>
    </div>
<?php
}

// Register the Quotes settings
function quotes_plugin_register_settings()
{

    // Add a field for selecting the Quotes Source
    add_settings_field(
        'quotes_source_field',      // Field ID
        'Select Quotes Source',     // Field title
        'quotes_source_field_callback',    // Callback function to display the field
        'quotes-plugin',            // Menu slug
        'quotes_source_section'     // Section ID
    );

    // Add a section for Quotes Source
    add_settings_section(
        'quotes_source_section',    // Section ID
        'Quotes Source',            // Section title
        'quotes_source_section_callback',  // Callback function to display the section
        'quotes-plugin'             // Menu slug
    );

    // Register the Quotes Source setting
    register_setting(
        'quotes-settings-group',    // Settings group
        'quotes_source'             // Setting name
    );
}
add_action('admin_init', 'quotes_plugin_register_settings');

function quotes_source_field_callback()
{
    echo '<p>Select a source for the quotes, "API" has a limit of request a second and "List" is a list withing the plugin has over 300 quotes. </p>';
    $selected_source = get_option('quotes_source'); // Get the currently selected source
    // Generate the options for the select field
    $options = array(
        'api' => 'API',
        'list' => 'List'
    );

    // Display the select field
    echo '<select name="quotes_source">';
    foreach ($options as $value => $label) {
        $selected = ($selected_source === $value) ? 'selected' : '';
        echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
    return $selected_source;
}

// Callback function to display the Quotes Source section
function quotes_source_section_callback()
{
    $output = '';

    $output .= '<p>Select a source for the quotes, "API" has a limit of request a second and "List" has over 300 quotes. </p>';
    $selected_source = get_option('quotes_source'); // Get the currently selected source
    // Generate the options for the select field
    $options = array(
        'api' => 'API',
        'list' => 'List'
    );

    // Build the select field
    $output .= '<select name="quotes_source">';
    foreach ($options as $value => $label) {
        $selected = ($selected_source === $value) ? 'selected' : '';
        $output .= '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
    }
    $output .= '</select>';

    return $selected_source;
}

// filter to add content to post
add_filter('the_content', 'q435_insert_to_bottom');


function q435_insert_to_bottom($content)
{
    $selectedValue = quotes_source_section_callback();

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

        /* foreach ($data as $item) {
            echo "Quote: " . $item['text'] . "<br>";
            echo "Author: " . $item['from'] . "<br>";
            echo "<br>";
        }*/

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
