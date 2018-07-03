# 👸 Zelda

**A more useful Link**

I don't want to store URLs for local content in the database: Neither do you. This allows you to store links to any kind of content in a better, safer way. It also allows you to define a bit of link metadata (i.e. text, target, etc).

## Features

- Select the content and taxonomy types *you* want to link to!
- *Never* store URLs in the database (all links are created at page-load)!
    - Well, except for `external` links, which are URLs by definition.
- Filter *everything* (**future release**)
- Add your own link types on the fly (**future release**)

## Usage

It's just a WordPress plugin, so plug it in!

Once you've done that, just add a Zelda field and then call it in your template like you'd do with any other ACF field:

```php
// page-template.php
the_field('example_zelda_field');
// <a href="/posts/a-post-you-linked-to" class="field-class class-the-user-set">Link Text!</a>
```

### Filters

- `src/Output.php`
    * `zelda/output/template` - The `sprintf` template used to generate links.
        1. the template string *(string)*
        2. the current instance of the Output object *(object)*
    * `zelda/output/element` - The HTML link element.
        1. the element string *(string)*
        2. the current instance of the Output object *(object)*
    * `zelda/output/class` - The array containing class(es).
        1. user & field classes *(array)*
        2. array with value, field, and post ID *(array)*
    * `zelda/output/content` - The content that will go inside the link tag.
        1. retrieved and processed content *(string)*
        2. array with value, field, and post ID *(array)*
    * `zelda/output/new-tab` - Whether or not the link should open in a new tab.
        1. should this open in a new tab *(boolean)*
        2. array with value, field, and post ID *(array)*
    * `zelda/output/attributes` - All the attributes.
        1. all attributes *(array)* - The attributes are passed as arrays with one or two elements.
        2. array with value, field, and post ID *(array)*
    * `zelda/output/destination/value` - The raw destination value, before processing.
        1. the destination value *(string)* - This is **not** the field value.
        2. array with value, field, and post ID *(array)*
    * `zelda/output/destination/type` - The raw destination type, before processing.
        1. the destination type *(string)*
        2. array with value, field, and post ID *(array)*
    * `zelda/output/destination` - The calculated destination.
        1. the destination *(string)*
        2. array with value, field, and post ID *(array)*
        