@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Crear Nuevo Ticket</h2>
            
            <form action="{{ route('customer.tickets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label for="name" class="form-label">Nombre del Ticket</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="mb-3">
            <label for="type" class="form-label">Tipo</label>
            <select class="form-control" id="type" name="type" required>
                <option value="">Seleccionar...</option>
                <option value="1">Tipo 1</option>
                <option value="2">Tipo 2</option>
                <option value="3">Tipo 3</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="transport_mode" class="form-label">Modo de Transporte</label>
            <select class="form-control" id="transport_mode" name="transport_mode" required>
                <option value="">Seleccionar...</option>
                <option value="air">Aéreo</option>
                <option value="sea">Marítimo</option>
                <option value="land">Terrestre</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="country" class="form-label">País</label>
            <input type="text" class="form-control" id="country" name="country" required>
        </div>
        
        <div class="mb-3">
            <label for="transported_product" class="form-label">Producto a Transportar</label>
            <input type="text" class="form-control" id="transported_product" name="transported_product" required>
        </div>
        
        <div class="mb-3">
            <label for="comments" class="form-label">Comentarios</label>
            <textarea class="form-control" id="comments" name="comments" rows="3"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="documents" class="form-label">Documentos</label>
            <input type="file" class="form-control" id="documents" name="documents[]" multiple accept=".pdf,.jpg,.png,.docx">
        </div>
        
        <button type="submit" class="btn btn-primary">Crear Ticket</button>
        <a href="{{ route('customer.tickets.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
        </div>
    </div>
</div>
@endsection