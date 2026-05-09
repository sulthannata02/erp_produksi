<h1>Dashboard Packing</h1>

<div style="display:flex; gap:20px;">
    <div style="padding:20px; background:green; color:white;">
        <h2>FG</h2>
        <h1>{{ $fg }}</h1>
    </div>

    <div style="padding:20px; background:red; color:white;">
        <h2>NG</h2>
        <h1>{{ $ng }}</h1>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="chart"></canvas>

<script>
const ctx = document.getElementById('chart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['FG', 'NG'],
        datasets: [{
            data: [{{ $fg }}, {{ $ng }}]
        }]
    }
});
</script>
<h3>Data Terbaru</h3>

<table border="1">
    <tr>
        <th>Production</th>
        <th>Status</th>
    </tr>

    @foreach($packings as $p)
    <tr>
        <td>{{ $p->production_id }}</td>
        <td>{{ $p->status }}</td>
    </tr>
    @endforeach
</table>