<table>
    <thead>
        <tr>
            <!-- <th>ID</th> -->
            <th>Nome</th>
            <!-- <th>Endereço</th> -->
            <!-- <th>Nascimento</th> -->
            <!-- <th>Celular</th> -->
            <!-- <th>Sexo</th> -->
            <!-- <th>Batizado</th> -->
            <th>Igreja</th>
            <th>Pagamento</th>
            <th>Status</th>
            <!-- <th>Obs</th> -->
            <!-- <th>Created At</th> -->
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
            <tr>
                <!-- <td>{{ $record->id }}</td> -->
                <td>{{ $record->nome }}</td>
                <!-- <td>{{ $record->data_nascimento }}</td> -->
                <!-- <td>{{ $record->sexo }}</td> -->
                <!-- <td>{{ $record->endereco }}</td> -->
                <!-- <td>{{ $record->celular }}</td> -->
                <!-- <td>{{ $record->batizado }}</td> -->
                <td>{{ $record->igreja }}</td>
                <td>{{ $record->tipo_pagamento }}</td>
                <td>{{ $record->situacao_pagamento }}</td>
                <!-- <td>{{ $record->observacao }}</td> -->
                <!-- <td>{{ $record->created_at }}</td> -->
            </tr>
        @endforeach
    </tbody>
</table>