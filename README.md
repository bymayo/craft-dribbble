> ⚠️ Deprecated - This Craft CMS 2.x plugin is no longer supported. Consider upgrading to the Craft CMS 3.x version.

⚡️**Looking for Craft CMS 3 Support?** [Dribbble for Craft CMS 3](https://github.com/bymayo/dribbble/)

<img src="https://github.com/bymayo/dribbble/blob/craft-2/screenshots/icon.png?raw=true" width="50">

# Dribbble for Craft CMS 2.x

Dribbble is a Craft CMS plugin that enables use of the Dribbble API to pull in shots, projects, user etc via Twig.

## Install

- Add the `dribbble` directory into your `craft/plugins` directory.
- Navigate to `Settings -> Plugins` and click the "Install" button.
- Navigate to `Settings -> Plugins` and click on settings for Dribbble (Little cog Icon)
- Register your application with Dribbble - https://dribbble.com/account/applications/new using the `Website URL` and `Callback URL` in the plugin settings.
- Once you've registered your application, copy the `Client ID` and `Client Secret` and fill in the correct fields in plugin settings.
- When your settings have saved click `Connect to Dribbble` and authorise Dribbble to connect to Craft CMS.

## Templating

There is only one available Twig tag to pull through anything from the Dribbble API.

The `get` tag allows you to pull anything from the Dribbble API. For example, if you want to output a list of shots by the authorised user, you would do the following:

```HTML
{% for shot in craft.dribbble.get('user/shots', 10) %}
	{{ shot.images.normal }}
{% endfor %}
```

The property/values the `get` method outputs depends on the option you specify. The property/values for the above example can be found under `Shots` (http://developer.dribbble.com/v2/shots)

## Options

<table>
	<tr>
		<td><strong>Name</strong></td>
		<td><strong>Type</strong></td>
		<td><strong>Default Value</strong></td>
		<td><strong>Description</strong></td>
	</tr>
	<tr>
		<td>GET</td>
		<td>string</td>
		<td>null</td>
		<td>You can use any GET option from the Dribbble API (http://developer.dribbble.com/v2/) e.g. `user/projects` or `user/shots`. Also check to see what property/values are output for each GET option</td>
	</tr>
	<tr>
		<td>Limit</td>
		<td>integer</td>
		<td>null</td>
		<td>Sets the limit on the amount of objects pulled from the API</td>
	</tr>
</table>

## Notes

Remember, Dribbble is spelt with 3 b's. Check your spellings 😎

## Roadmap

- Fieldtype to select specific shots to show in an entry
