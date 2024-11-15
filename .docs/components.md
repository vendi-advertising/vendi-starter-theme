# Components

## Field Types

### Text

The text field is a single line text input used for storing small amounts of text data. This field type does not
support any metadata such as links, formatting or line breaks.

Some text field widgets impose additional restrictions on the value of the data that can be entered. For example, the
email widget requires that the value be in the form of `something@example`.

Any documentation that identifies a text field type is assumed to be a single line text input unless otherwise
specified.

#### Widgets

- Single line text input
- Email
- URL
- Password
- Telephone

#### Usage notes

One of the main considerations when choosing between a text field and a textarea field is the amount of text that is
expected to be entered. Although it is not always possible to determine an upper end to the amount of text,
text fields generally encourage a limited amount, while a textarea field implies

Besides the amount of text expected to be entered, the admin experience, specifically the horizontal space for the
field, should be taken into consideration when choosing between a text field and a textarea field. For example, if
the admin experience includes multiple columns, it might be easier for content admins to scan and enter content
into a textarea instead of a text field.

### Textarea

The textarea field type is the same as the text field type but allows for multiple lines of text. This field type should
be used

#### Widgets

The textarea field has a single widget

- Textarea

### WYSIWYG

## Components

### Basic Copy

#### Fields

##### Component

###### Copy

| Field name | Field type | Required       | Default value | Description           |
|------------|------------|----------------|---------------|-----------------------|
| Copy       | WYSIWYG    | No<sup>1</sup> |               | The copy to display   |
| Text Color | Color      | Yes            | Black         | The color of the text |

#### Component notes

1. Although the copy field is not required, if it is not provided, the component will not be rendered.

