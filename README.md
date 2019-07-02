# 👸 Zelda

**A more useful Link**

I don't want to store URLs for local content in the database: Neither do you. This allows you to store links to any kind of content in a better, safer way. It also allows you to define a bit of link metadata (i.e. text, target, etc).

## Features

- Select the content and taxonomy types *you* want to link to!
- *Never* store URLs in the database (all links are created at page-load)!
    - Well, except for `external` links, which are URLs by definition.
        - ...But it does try and guess if external links are local, and saves them without a host if they are (so you don't end up with staging URLs on production).
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
