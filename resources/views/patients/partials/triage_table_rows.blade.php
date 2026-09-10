@forelse($triageRecords as $index => $record)
    <tr data-risk="{{ strtolower($record->risk_level) }}" data-status="{{ strtolower($record->status) }}">
        
        {{-- Sequential position in the current active queue (matches the Nurse-side table) --}}
        <td class="font-bold">#{{ $index + 1 }}</td>

        <td class="font-medium">
            {{ $record->patient ? ($record->patient->first_name . ' ' . $record->patient->last_name) : 'Unknown Patient' }}
        </td>
        <td class="text-muted">{{ $record->service_type }}</td>
        
        <td class="text-center">
            @php
                $factorsList = is_array($record->risk_factors) ? implode(' | ', $record->risk_factors) : '';
            @endphp
            @if(strtolower($record->risk_level) == 'high')
                <span class="pill high" title="{{ $factorsList }}">High Risk{{ $record->risk_score !== null ? ' ('.$record->risk_score.')' : '' }}</span>
            @elseif(strtolower($record->risk_level) == 'medium')
                <span class="pill medium" title="{{ $factorsList }}">Medium Risk{{ $record->risk_score !== null ? ' ('.$record->risk_score.')' : '' }}</span>
            @else
                <span class="pill low" title="{{ $factorsList }}">Low Risk{{ $record->risk_score !== null ? ' ('.$record->risk_score.')' : '' }}</span>
            @endif

            @if($record->status === 'Called')
                <span style="display:block; font-size:10px; color:#ffc107; font-weight:bold; margin-top:2px;">📢 NOW CALLING...</span>
            @endif
        </td>

        <td class="text-right pad-right" style="white-space: nowrap; vertical-align: middle;">
            <div style="display: inline-flex; gap: 8px; justify-content: flex-end; align-items: center; width: 100%;">
                
                <button type="button" class="btn-sm btn-view" 
                        data-patient='@json($record->patient)' 
                    data-record='@json($record)'
                    onclick="openTriageModal(this)">
                    View
                </button>

                @if(strtolower(auth()->user()->role) === 'doctor')
                    @php $status = $record->status ?? 'Waiting'; @endphp
                    
                    @if($status == 'Waiting')
                        <button type="button" class="btn-action" style="background-color: #10B981; color: white; border: none; padding: 6px 12px; border-radius: 4px;" 
                                onclick="updateQueueStatus({{ $record->id }}, 'Called')">
                            Call Patient
                        </button>
                    @elseif($status == 'Called')
                        <button type="button" class="btn-action" style="background-color: #F59E0B; color: white; border: none; padding: 6px 12px; border-radius: 4px;" 
                                onclick="updateQueueStatus({{ $record->id }}, 'In Session')">
                            Start Session
                        </button>
                    @elseif($status == 'In Session')
                        <button type="button" class="btn-action" style="background-color: #EF4444; color: white; border: none; padding: 6px 12px; border-radius: 4px;" 
                                onclick="updateQueueStatus({{ $record->id }}, 'Done')">
                            Done
                        </button>
                    @endif
                @endif

            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="empty-state">Queue is currently empty.</td>
    </tr>
@endforelse