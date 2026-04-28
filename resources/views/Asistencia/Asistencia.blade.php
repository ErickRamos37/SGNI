{{-- resources/views/Asistencia/Asistencia.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Asistencia - UABC FIAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[#f8fafc] text-slate-700">

    <div class="flex min-h-screen">
        
        @include('layouts.sidebar')

        <main class="flex-1 flex flex-col overflow-hidden p-10">
            
            <header class="mb-8">
                <h2 class="text-[28px] font-normal text-slate-800">Registro de Asistencia</h2>
                <p class="text-slate-500 text-sm mt-1">Carga de archivo Excel y control de asistencia semanal</p>
            </header>

            <section class="mb-8">
                <div class="border-2 border-dashed border-[#f29100] rounded-2xl bg-white p-12 flex flex-col items-center justify-center transition-colors hover:bg-slate-50 cursor-pointer">
                    <div class="text-[#f29100] mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M8 13h2"/><path d="M8 17h2"/><path d="M14 13h2"/><path d="M14 17h2"/></svg>
                    </div>
                    <h3 class="text-[#007141] font-semibold text-lg">Carga de Archivo Excel</h3>
                    <p class="text-slate-500 text-sm mt-2 mb-6">Arrastra tu archivo Excel aquí o haz clic para seleccionar</p>
                    
                    <button class="bg-[#007141] hover:bg-[#005e36] text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                        Seleccionar Archivo
                    </button>
                </div>
            </section>

            <section class="grid grid-cols-2 gap-8 mb-6">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-slate-500">Curso</label>
                    <div class="relative">
                        <select class="w-full appearance-none bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-[#007141]/20 focus:border-[#007141] cursor-pointer shadow-sm">
                            <option>Propedéutico</option>
                            <option>Inducción</option>
                        </select>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-semibold text-slate-500">Grupo</label>
                    <div class="relative">
                        <select class="w-full appearance-none bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-[#007141]/20 focus:border-[#007141] cursor-pointer shadow-sm">
                            <option>Grupo A - Ingeniería</option>
                            <option>Grupo B - Arquitectura</option>
                        </select>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </span>
                    </div>
                </div>
            </section>

            <section class="flex-1 overflow-auto pb-10">
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 tracking-wider w-32">MATRÍCULA</th>
                                <th class="px-6 py-4 text-xs font-semibold text-slate-500 tracking-wider">NOMBRE</th>
                                <th class="px-4 py-4 text-xs font-semibold text-slate-500 tracking-wider text-center">LUNES 1</th>
                                <th class="px-4 py-4 text-xs font-semibold text-slate-500 tracking-wider text-center">MARTES 2</th>
                                <th class="px-4 py-4 text-xs font-semibold text-slate-500 tracking-wider text-center">MIÉRCOLES 3</th>
                                <th class="px-4 py-4 text-xs font-semibold text-slate-500 tracking-wider text-center">JUEVES 4</th>
                                <th class="px-4 py-4 text-xs font-semibold text-slate-500 tracking-wider text-center">VIERNES 5</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">369883</td>
                                <td class="px-6 py-4 text-sm text-slate-600">Melquicedec Luis Vicente</td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">1234568</td>
                                <td class="px-6 py-4 text-sm text-slate-600">María Fernanda López</td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                                <td class="px-4 py-4 text-center"><input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-[#007141] focus:ring-[#007141] cursor-pointer"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end mt-6">
                    <button class="bg-[#007141] hover:bg-[#005e36] text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-all shadow-sm active:scale-95">
                        Guardar Asistencia
                    </button>
                </div>
            </section>
            </section>
        </main>
    </div>
</body>
</html>