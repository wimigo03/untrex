<td class="text-center p-1">
    <table style="border-collapse:collapse; border: none;">
        <tr>
            <td style="padding: 0;">
                <a href="{{route('comprobantes.fiscales.show', $comprobante_id )}}" class="btn btn-xs btn-info">
                    <i class="fas fa-eye" aria-hidden="true"></i>
                </a>
            </td>
            <td style="padding: 0;">
                @if($status != 0)
                    <a href="#" class="btn btn-xs btn-secondary">
                        <i class="fas fa-edit" aria-hidden="true"></i>
                    </a>
                @else
                    <a href="{{route('comprobantesfiscalesdetalles.create', $comprobante_id)}}" class="btn btn-xs btn-warning">
                        <i class="fas fa-edit" aria-hidden="true"></i>
                    </a>
                @endif
            </td>
            <td style="padding: 0;">
                <a href="{{route('comprobantes.fiscales.pdf', $comprobante_id)}}" class="btn btn-xs btn-danger" target="_blank">
                    <i class="fas fa-file-pdf" aria-hidden="true"></i>
                </a>
            </td>
        </tr>
    </table>
</td>


