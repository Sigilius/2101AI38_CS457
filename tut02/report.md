
# CS457: Lab Assignment 02 - Asymmetric Encryption

## Table of Contents
1. [Introduction](#introduction)
2. [Objectives](#objectives)
3. [RSA Implementation](#rsa-implementation)
    - [Key Generation](#key-generation)
    - [Encryption and Decryption](#encryption-and-decryption)
    - [Handling Different Message Sizes](#handling-different-message-sizes)
4. [Security Analysis](#security-analysis)
    - [Identified Vulnerabilities](#identified-vulnerabilities)
    - [Mitigation Strategies](#mitigation-strategies)
    - [Key Size Impact Analysis](#key-size-impact-analysis)
    - [Side-Channel Attacks](#side-channel-attacks)
5. [Performance Testing & Results](#performance-testing-results)
    - [Encryption & Decryption Time](#encryption-decryption-time)
    - [Memory Usage Analysis](#memory-usage-analysis)
6. [Optimization Strategies](#optimization-strategies)
7. [Conclusion](#conclusion)

## Introduction
RSA (Rivest-Shamir-Adleman) is a widely used asymmetric cryptographic algorithm designed for secure data transmission. This lab focuses on the practical implementation of RSA encryption and a detailed analysis of its security properties, vulnerabilities, and performance metrics.

## Objectives
- Implement RSA key pair generation, encryption, and decryption.
- Manage different message sizes and ensure robust error handling.
- Identify security vulnerabilities and propose mitigation strategies.
- Analyze RSA performance across different key sizes.
- Evaluate CPU utilization, memory consumption, and execution time.

## RSA Implementation

### Key Generation
1. **Generate Two Large Prime Numbers (p, q):** 
   - Use probabilistic methods for prime number generation.
2. **Compute Modulus (n):** 
   - \( n = p \times q \).
3. **Compute Euler's Totient Function (\( \phi(n) \)):** 
   - \( \phi(n) = (p - 1) \times (q - 1) \).
4. **Select Public Exponent (e):** 
   - Choose \( e \) such that \( 1 < e < \phi(n) \) and \( gcd(e, \phi(n)) = 1 \).
5. **Compute Private Exponent (d):** 
   - \( d \) is the modular inverse of \( e \) modulo \( \phi(n) \).

### Encryption and Decryption
- **Encryption:** \( C = M^e \mod n \)
- **Decryption:** \( M = C^d \mod n \)

### Handling Different Message Sizes
- **Chunk-Based Encryption:** Split messages into smaller blocks to fit within the modulus size.
- **Hybrid Encryption:** Use RSA to encrypt symmetric keys, which are then used to encrypt the actual data.

## Security Analysis

### Identified Vulnerabilities
| Vulnerability       | Description                                                |
|---------------------|------------------------------------------------------------|
| Small Key Size      | Easily compromised through brute-force attacks.            |
| Padding Attacks     | Vulnerable to chosen ciphertext attacks without proper padding. |
| Side-Channel Attacks| Implementation may leak information through timing analysis.|

### Mitigation Strategies
| Mitigation                         | Description                                              |
|------------------------------------|----------------------------------------------------------|
| Use 2048-bit Keys or Higher        | Ensures resistance against brute-force attacks.           |
| Optimal Asymmetric Encryption Padding (OAEP) | Protects against padding oracle attacks.                |
| Implement Constant-Time Algorithms | Defends against timing attacks by ensuring uniform execution times. |

### Key Size Impact Analysis
| Key Size | Security Level         | Performance Impact               |
|----------|------------------------|----------------------------------|
| 1024-bit | Weak (easily breakable) | Fast encryption/decryption but insecure. |
| 2048-bit | Strong (recommended)    | Moderate speed, good security.   |
| 4096-bit | Very Strong             | Slow operations, high security.  |

### Side-Channel Attacks
- **Timing Attacks:** Measure the time taken for cryptographic operations.
- **Power Analysis:** Observe power consumption to extract cryptographic keys.
- **Countermeasures:** Utilize blinding techniques and introduce random delays to obscure operational patterns.

## Performance Testing & Results

### Encryption & Decryption Time
#### Key Size: 1024-bit
| Message Size | Encryption Time (s) | Decryption Time (s) |
|--------------|---------------------|---------------------|
| 16 bytes     | 0.0006              | 0.0811             |
| 128 bytes    | 0.0047              | 0.6578              |
| 512 bytes    | 0.0192              | 2.6321              |
| 1024 bytes   | 0.0379              | 5.3361              |

#### Key Size: 2048-bit
| Message Size | Encryption Time (s) | Decryption Time (s) |
|--------------|---------------------|---------------------|
| 16 bytes     | 0.0025              | 1.0404             |
| 128 bytes    | 0.0136              | 4.3311              |
| 512 bytes    | 0.0553              | 17.9870             |
| 1024 bytes   | 0.1453              | 35.6581             |

#### Key Size: 4096-bit
| Message Size | Encryption Time (s) | Decryption Time (s) |
|--------------|---------------------|---------------------|
| 16 bytes     | 0.0052              | 3.7983              |
| 128 bytes    | 0.0557              | 32.2574             |
| 512 bytes    | 0.1728              | 131.7794            |
| 1024 bytes   | 0.3512              | 261.0404            |

![image1](tut02/plot.png)

![image2](tut02/plot2.png)

## Optimization Strategies
| Optimization                  | Description                                                      |
|-------------------------------|------------------------------------------------------------------|
| Hybrid Encryption             | Encrypt symmetric keys only, reducing RSA computational load.    |
| Optimized Prime Generation    | Use efficient algorithms for prime number selection.             |
| Parallel Processing           | Leverage multi-threading to accelerate RSA computations.         |
| Fast Modular Exponentiation   | Implement Montgomery multiplication for efficient calculations. |

## Conclusion
The RSA encryption implementation was successfully completed and analyzed. The study highlighted how larger key sizes enhance security but introduce computational overhead. Performance optimizations such as hybrid encryption, efficient key generation, and parallel processing can significantly improve RSA's efficiency while maintaining robust security standards.
