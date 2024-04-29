
    {{--  @foreach($roles as $role)
        <div class="checkbox">
                @if ($role->name != 'SuperAdministrador')
                    <input name="roles[]" type="checkbox" value="{{$role->id}}" {{$user->roles->contains($role->id)?'checked':''}}>
                    {{$role->name}}<br>
                    <small class="text-muted">{{$role->permissions->pluck('name')->implode(', ')}}</small>
                @endif  
        </div>
    @endforeach  --}}

    <select class="form-select" style="width:100%" data-placeholder="Selecciona roles" multiple="multiple" name="roles[]" id="roles" >
            @foreach($roles as $role)
                <option {{ collect(old('roles', $user->roles->pluck('id')))->contains($role->id)? 'selected':''}} value="{{$role->id}}">{{$role->name}}</option>   
            @endforeach             
        </select>
            @include('admin.partials.mensages_error', ['nombre' => 'roles'])





