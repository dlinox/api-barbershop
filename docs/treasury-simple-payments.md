# Sistema de Pagos - Treasury (Simple)

## 🎯 Solo Pagos y Adelantos

Sistema ultra simple que **usa la configuración ya existente** en los perfiles:

1. **👷 Trabajadores**: Agregar campos a `profile_workers`
2. **✂️ Barberos**: Usar `profile_barbers.commission_percentage` (ya existe)
3. **👨‍🏫 Docentes**: Usar `academy_group_teachers.hourly_rate` (ya existe)

## 🗃️ Configuración en Perfiles

### **profile_workers** (se agregan 2 campos)
```sql
ALTER TABLE profile_workers ADD:
  - monthly_salary DECIMAL(10,2)
  - payment_frequency ENUM('monthly', 'biweekly')
```

### **profile_barbers** (ya tiene)
```sql
✅ commission_percentage DECIMAL(5,2)
```

### **academy_group_teachers** (ya tiene)
```sql
✅ hourly_rate DECIMAL(10,2)
✅ holiday_hourly_rate DECIMAL(10,2)
```

## 📊 Solo 2 Tablas Nuevas

### **treasury_employee_payments**
Pagos realizados (polimórfica).
```php
employee_type: 'worker'/'barber'/'teacher'
employee_id: ID del profile
period: "Marzo 2026"
base_amount, bonus, deductions, total_amount
calculation_details: JSON
```

### **treasury_employee_advances**
Adelantos de sueldo (polimórfica).
```php
employee_type: 'worker'/'barber'/'teacher'
employee_id: ID del profile
amount, status: 'pending'/'discounted'
```

## 💰 Cálculos

### 👷 Trabajadores
```php
// Dato: profile_workers
$worker = ProfileWorker::find($id);

$pago = $worker->monthly_salary;
// o proporcional: ($monthly_salary / 30) * $dias_trabajados

$neto = $pago + $bonos - $adelantos;
```

### ✂️ Barberos
```php
// Dato: profile_barbers.commission_percentage
$barber = ProfileBarber::find($id);

$ventas = BarbershopTicket::where('profile_barber_id', $id)
    ->whereBetween('ticket_date', [$start, $end])
    ->sum('total');

$comision = $ventas * ($barber->commission_percentage / 100);
$neto = $comision + $bonos - $adelantos;
```

### 👨‍🏫 Docentes
```php
// Dato: academy_group_teachers por grupo
$groupTeachers = AcademyGroupTeacher::where('teacher_id', $teacher_id)
    ->where('status', 'active')
    ->get();

$total = 0;
foreach ($groupTeachers as $gt) {
    $attendances = AcademyTeacherAttendance::where('teacher_id', $teacher_id)
        ->where('group_id', $gt->group_id)
        ->whereBetween('date', [$start, $end])
        ->get();

    $regular = $attendances->where('is_holiday', false)->sum('hours_worked');
    $holiday = $attendances->where('is_holiday', true)->sum('hours_worked');

    $total += ($regular * $gt->hourly_rate) + ($holiday * $gt->holiday_hourly_rate);
}

$neto = $total + $bonos - $adelantos;
```

## 🔄 Flujo Simple

### 1. Registrar Pago
```php
TreasuryEmployeePayment::create([
    'employee_type' => 'worker',        // o 'barber', 'teacher'
    'employee_id' => $worker->id,
    'infrastructure_id' => $branch_id,
    'period' => 'Marzo 2026',
    'period_start' => '2026-03-01',
    'period_end' => '2026-03-31',
    'base_amount' => 1500.00,
    'bonus' => 100.00,
    'deductions' => 200.00,
    'total_amount' => 1400.00,
    'calculation_details' => json_encode([
        'monthly_salary' => 1500,
        'worked_days' => 30
    ]),
    'payment_date' => now(),
    'payment_method_id' => $method_id,
    'paid_by' => auth()->id()
]);
```

### 2. Registrar Adelanto
```php
TreasuryEmployeeAdvance::create([
    'employee_type' => 'worker',
    'employee_id' => $worker->id,
    'amount' => 500.00,
    'advance_date' => now(),
    'status' => 'pending',              // luego 'discounted'
    'payment_method_id' => $method_id,
    'authorized_by' => $admin_id,
    'paid_by' => auth()->id()
]);
```

### 3. Descontar Adelanto en Pago
```php
// Al crear el pago:
$adelantos = TreasuryEmployeeAdvance::where('employee_type', 'worker')
    ->where('employee_id', $worker_id)
    ->where('status', 'pending')
    ->sum('amount');

$payment = TreasuryEmployeePayment::create([
    ...
    'deductions' => $adelantos,
    'total_amount' => $base_amount + $bonus - $adelantos
]);

// Marcar adelantos como descontados
TreasuryEmployeeAdvance::where('status', 'pending')
    ->update([
        'status' => 'discounted',
        'discounted_in_payment_id' => $payment->id
    ]);
```

## 📊 Calculation Details

### Trabajadores
```json
{
    "monthly_salary": 1500,
    "payment_frequency": "monthly",
    "worked_days": 30,
    "total_days": 30
}
```

### Barberos
```json
{
    "commission_percentage": 15,
    "total_sales": 5000,
    "services_count": 45,
    "commission_amount": 750
}
```

### Docentes
```json
{
    "groups": [
        {
            "group_id": 1,
            "group_name": "Grupo A",
            "hourly_rate": 25,
            "holiday_hourly_rate": 35,
            "regular_hours": 40,
            "holiday_hours": 4,
            "subtotal": 1140
        }
    ],
    "total_hours": 44,
    "total_earned": 1140
}
```

## 🚀 Quick Start

```bash
# 1. Migrar (agrega campos a profile_workers + 2 tablas)
php artisan migrate

# 2. Ver configuración actual
php artisan db:seed --class=TreasuryEmployeeSeeder

# 3. Configurar workers (ejemplo)
UPDATE profile_workers
SET monthly_salary = 1500.00,
    payment_frequency = 'monthly'
WHERE id = 1;
```

## ✅ Ventajas

- ✅ **Ultra simple**: Solo 2 tablas nuevas
- ✅ **Usa lo que ya tienes**: No duplica configuración
- ✅ **Polimórfico**: Una tabla de pagos para todos
- ✅ **Mínimo código**: Todo ya existe en los perfiles

---

**¡Así de simple!** 🎉