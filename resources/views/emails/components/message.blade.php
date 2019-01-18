@isset($image)
    @include('emails.components.image')
@endisset

<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0 auto;">
    <tr>
        <td style="background-color: #ffffff;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding: 20px; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                        {{ $slot }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@isset($button)
    @include('emails.components.button')
@endisset
