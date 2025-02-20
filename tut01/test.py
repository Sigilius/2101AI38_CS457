import time
import tracemalloc
import psutil
import matplotlib.pyplot as plt
from aes import encrypt, decrypt  # Ensure these functions are implemented correctly

# Measure speed of encryption and decryption for different input sizes
def measure_speed():
    key = 'my secret key'
    input_sizes = [10, 100, 1000, 10000, 100000]
    results = []

    for size in input_sizes:
        message = b'0' * size

        # Measure encryption time
        start = time.time()
        encrypted = encrypt(key, message)
        encryption_time = time.time() - start

        # Measure decryption time
        start = time.time()
        decrypt(key, encrypted)
        decryption_time = time.time() - start

        results.append((size, encryption_time, decryption_time))

    return results

# Measure memory usage during encryption and decryption for different input sizes
def measure_memory():
    key = 'my secret key'
    input_sizes = [10, 100, 1000, 10000, 100000]
    memory_results_enc = []
    memory_results_dec = []

    for size in input_sizes:
        message = b'0' * size

        # Measure encryption memory usage
        tracemalloc.start()
        encrypted = encrypt(key, message)
        current_enc, peak_enc = tracemalloc.get_traced_memory()
        tracemalloc.stop()

        # Measure decryption memory usage
        tracemalloc.start()
        decrypt(key, encrypted)
        current_dec, peak_dec = tracemalloc.get_traced_memory()
        tracemalloc.stop()

        memory_results_enc.append((size, current_enc / 1024, peak_enc / 1024))  # Convert to KB
        memory_results_dec.append((size, current_dec / 1024, peak_dec / 1024))  # Convert to KB

    return memory_results_enc, memory_results_dec

# Measure CPU usage during encryption and decryption for different input sizes
def measure_cpu():
    key = 'my secret key'
    input_sizes = [1000, 10000, 100000]
    cpu_results_enc = []
    cpu_results_dec = []
    process = psutil.Process()

    for size in input_sizes:
        message = b'0' * size

        # Measure CPU usage during encryption
        cpu_before = process.cpu_percent(interval=None)
        encrypt(key, message)
        cpu_after = process.cpu_percent(interval=None)
        cpu_enc = cpu_after - cpu_before

        # Measure CPU usage during decryption
        cpu_before = process.cpu_percent(interval=None)
        decrypt(key, encrypt(key, message))
        cpu_after = process.cpu_percent(interval=None)
        cpu_dec = cpu_after - cpu_before

        cpu_results_enc.append((size, cpu_enc))
        cpu_results_dec.append((size, cpu_dec))

    return cpu_results_enc, cpu_results_dec

# Visualize results (speed, memory, and CPU usage for all input sizes)
def visualize_results(speed_results, memory_enc_results, memory_dec_results, cpu_enc_results, cpu_dec_results):
    # Speed Visualization
    sizes = [result[0] for result in speed_results]
    enc_times = [result[1] for result in speed_results]
    dec_times = [result[2] for result in speed_results]

    plt.figure(figsize=(10, 6))
    plt.plot(sizes, enc_times, label='Encryption Time', marker='o')
    plt.plot(sizes, dec_times, label='Decryption Time', marker='o')
    plt.title('Encryption/Decryption Time vs Input Size')
    plt.xlabel('Input Size (bytes)')
    plt.ylabel('Time (seconds)')
    plt.legend()
    plt.grid(True)
    plt.savefig('speed_results.png')  # Save the plot as an image
    plt.close()

    # Memory Usage Visualization
    enc_sizes = [result[0] for result in memory_enc_results]
    enc_current_usage = [result[1] for result in memory_enc_results]
    enc_peak_usage = [result[2] for result in memory_enc_results]
    dec_current_usage = [result[1] for result in memory_dec_results]
    dec_peak_usage = [result[2] for result in memory_dec_results]

    fig, ax = plt.subplots(figsize=(10, 6))
    ax.plot(enc_sizes, enc_current_usage, label='Encryption Current Usage (KB)', marker='o')
    ax.plot(enc_sizes, enc_peak_usage, label='Encryption Peak Usage (KB)', marker='o')
    ax.plot(enc_sizes, dec_current_usage, label='Decryption Current Usage (KB)', marker='o')
    ax.plot(enc_sizes, dec_peak_usage, label='Decryption Peak Usage (KB)', marker='o')
    ax.set_title('Memory Usage During Encryption and Decryption')
    ax.set_xlabel('Input Size (bytes)')
    ax.set_ylabel('Memory Usage (KB)')
    ax.legend()
    plt.grid(True)
    plt.savefig('memory_results.png')  # Save the plot as an image
    plt.close()

    # CPU Utilization Visualization
    cpu_sizes = [result[0] for result in cpu_enc_results]
    enc_cpu = [result[1] for result in cpu_enc_results]
    dec_cpu = [result[1] for result in cpu_dec_results]

    plt.figure(figsize=(10, 6))
    plt.plot(cpu_sizes, enc_cpu, label='Encryption CPU Utilization (%)', marker='o')
    plt.plot(cpu_sizes, dec_cpu, label='Decryption CPU Utilization (%)', marker='o')
    plt.title('CPU Utilization During Encryption and Decryption')
    plt.xlabel('Input Size (bytes)')
    plt.ylabel('CPU Utilization (%)')
    plt.legend()
    plt.grid(True)
    plt.savefig('cpu_results.png')  # Save the plot as an image
    plt.close()

# Print results and visualize
def print_and_visualize():
    # Measure speed, memory, and CPU utilization
    speed_results = measure_speed()
    memory_enc_results, memory_dec_results = measure_memory()
    cpu_enc_results, cpu_dec_results = measure_cpu()

    # Print Speed Results
    print("\n### Speed Results ###")
    print("Input Size (bytes) | Encryption Time (s) | Decryption Time (s)")
    for size, enc_time, dec_time in speed_results:
        print(f"{size:<17} | {enc_time:<20} | {dec_time}")

    # Print Memory Results
    print("\n### Memory Results ###")
    for size, current_enc, peak_enc in memory_enc_results:
        print(f"Input Size: {size:<7} | Encryption (Current, Peak): {current_enc:.2f} KB, {peak_enc:.2f} KB")
    for size, current_dec, peak_dec in memory_dec_results:
        print(f"Input Size: {size:<7} | Decryption (Current, Peak): {current_dec:.2f} KB, {peak_dec:.2f} KB")

    # Print CPU Results
    print("\n### CPU Utilization Results ###")
    for size, cpu_enc in cpu_enc_results:
        print(f"Input Size: {size:<7} | Encryption CPU Utilization: {cpu_enc:.2f}%")
    for size, cpu_dec in cpu_dec_results:
        print(f"Input Size: {size:<7} | Decryption CPU Utilization: {cpu_dec:.2f}%")

    # Visualize Results
    visualize_results(speed_results, memory_enc_results, memory_dec_results, cpu_enc_results, cpu_dec_results)

if __name__ == "__main__":
    print_and_visualize()
