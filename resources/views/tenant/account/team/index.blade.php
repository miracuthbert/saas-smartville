@extends('tenant.layouts.default')

@section('title', page_title('Team'))

@section('tenant.breadcrumb')
    <li class='breadcrumb-item active'>Team</li>
@endsection

@section('tenant.content')
    <div class="card mb-3">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex justify-content-between align-content-center">
                    <div>
                        <h4>Team Members</h4>
                        <!-- todo: Show team member count versus subscription -->
                        <p>Members: {{ $members->total() }}</p>
                    </div>

                    <aside>
                        <a href="{{ route('tenant.account.team.create') }}">Add new member</a>
                    </aside>
                </div>
            </div>
            <hr>

            @if($members->count())
                <div class="table-responsive-sm mb-3">
                    <table class="table table-hover table-borderless">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Roles</th>
                            <th>Joined</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>
                                    @foreach($member->companyRoles as $role)
                                        <span class="badge badge-primary badge-pill" data-toggle="tooltip"
                                              title="{{ optional($role->pivot->expires_at)->diffForHumans() }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>
                                    <span title="{{ $member->pivot->created_at }}">
                                        {{ $member->pivot->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-link" role="button"
                                           href="{{ route('tenant.account.team.edit', $member) }}">
                                            <i class="fa fa-edit"></i> <span class="sr-only">Edit</span>
                                        </a>
                                        @if(!auth()->user()->isTheSameAs($member))
                                            <a class="btn btn-link" role="button"
                                               href="{{ route('tenant.account.team.destroy', $member) }}"
                                               onclick="event.preventDefault(); document.getElementById('delete-member-{{ $member->id }}').submit()">
                                                <i class="fa fa-trash"></i> <span class="sr-only">Delete</span>
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Delete Form --}}
                                    <form action="{{ route('tenant.account.team.destroy', $member) }}" method="POST"
                                          id="delete-member-{{ $member->id }}" style="display: none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <caption>
                            @if($members->perPage() < $members->total())
                                {{ $members->links() }}
                            @endif
                        </caption>
                    </table>
                </div>
            @else
                <p>No team members found.</p>
            @endif
        </div>
    </div>
@endsection