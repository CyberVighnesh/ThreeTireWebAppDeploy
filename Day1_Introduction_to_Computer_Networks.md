# Day 1: Introduction to Computer Networks

## What Will You Learn?

- What a computer network is and why we need it
- Different types of networks (LAN, WAN, MAN, PAN)
- Different network topologies (Star, Bus, Ring, Mesh, Hybrid)
- Difference between Client-Server and Peer-to-Peer networks
- How these basics connect to Cyber Security
- Basic terms used every day in networking and security

---

## Introduction

Every time you open WhatsApp, watch a YouTube video, or send a payment through a UPI app, your device is **talking** to another device somewhere else. This "talking" happens through a **network**.

A network is simply a group of devices (computers, mobiles, servers) connected together so they can share data. Without networks, the internet, banking apps, and even college Wi-Fi would not work.

For a Cyber Security student, understanding networks is the first step. You cannot protect something you don't understand. Almost every cyber attack — hacking, data theft, virus spread — happens **through** a network.

---

## Basic Definition

**Definition:**
A computer network is a group of two or more computers or devices connected together to share data, resources, and communication.

**In simple words:**
Think of a network like a group of friends connected by phone calls. Any friend can call another friend and share information instantly. Similarly, computers "call" (connect to) each other and share files, messages, or internet access.

---

## Real-Life Example

Imagine you are in your Nagpur college computer lab. All 40 computers are connected to one Wi-Fi router. When your teacher shares a PDF file on the shared drive, every student's computer can access it instantly — that is a network in action.

Another everyday example: When you order food using Zomato or Swiggy, your mobile phone (client) sends a request through the mobile network and internet to Zomato's server, which processes your order and sends back a confirmation. This entire journey happens over multiple connected networks.

---

## How It Works

Step 1 → Your device wants to send data (a message, file, or request)
Step 2 → It converts the data into small packets
Step 3 → Packets travel through cables, Wi-Fi, or mobile towers to the network
Step 4 → The network devices (router, switch) forward the packets to the correct destination
Step 5 → The receiving device collects the packets and reconstructs the data

---

## Diagram

```
   Your Laptop
        |
        v
    Wi-Fi Router
        |
        v
     Internet
        |
        v
   Remote Server (e.g., Zomato)
```

**How to understand this diagram:**
Your laptop is the sender. The Wi-Fi router is the local device that connects you to the outside world. The internet is a huge network of networks. The remote server is the destination that processes your request and replies back.

---

## Important Components

| Component | Simple Meaning | Purpose |
|---|---|---|
| Node | Any device connected to the network (PC, mobile, printer) | Sends or receives data |
| Router | A device that connects different networks | Directs data to the correct destination |
| Switch | A device that connects devices within one network | Passes data between devices in the same LAN |
| Server | A powerful computer that provides services | Stores data or runs applications for other devices |
| Client | A device that requests services | Asks the server for data or a service |

---

## Types / Categories

### Type 1 — LAN (Local Area Network)

- **Meaning:** A network that covers a small area like one building or office
- **How it works:** Devices are connected using cables or Wi-Fi within a limited range
- **Simple example:** Your college computer lab or a small office in Nagpur where all computers share one internet connection
- **Security importance:** LANs are easier to secure because you control the physical space, but an attacker who gets physical access can cause a lot of damage

### Type 2 — WAN (Wide Area Network)

- **Meaning:** A network that covers a large geographical area — even across cities or countries
- **How it works:** Uses long-distance links like fiber optic cables, satellites, or leased telecom lines
- **Simple example:** The Internet itself is the biggest WAN. A company with offices in Nagpur and Mumbai connected together is also a WAN
- **Security importance:** WANs pass through many networks owned by different companies, so data can be intercepted; encryption is very important here

### Type 3 — MAN (Metropolitan Area Network)

- **Meaning:** A network that covers a city or a large campus
- **How it works:** Connects multiple LANs within a city using high-speed links
- **Simple example:** A cable TV network or a city-wide Wi-Fi network connecting different branches of a bank in Nagpur
- **Security importance:** Since it spans a wide public area, MANs need strong monitoring to prevent unauthorized access points

### Type 4 — PAN (Personal Area Network)

- **Meaning:** A very small network around one person, usually within a few meters
- **How it works:** Connects personal devices using Bluetooth or short-range wireless
- **Simple example:** Connecting your mobile phone to wireless earphones or a smartwatch
- **Security importance:** Bluetooth devices can be hijacked if not paired securely, so PAN security matters even for personal gadgets

---

## Network Topologies (How Devices Are Arranged)

### Star Topology
All devices connect to one central device (like a switch). Easy to manage and troubleshoot. If the central device fails, the whole network goes down.

```
        [Switch]
       /   |   \
   PC1   PC2   PC3
```

### Bus Topology
All devices share a single central cable. Simple and cheap, but if the main cable breaks, the entire network stops.

```
PC1 --- PC2 --- PC3 --- PC4
      (single shared cable)
```

### Ring Topology
Devices are connected in a circular manner, and data travels in one direction. If one connection breaks, the whole ring can be affected.

```
PC1 -- PC2
 |       |
PC4 -- PC3
```

### Mesh Topology
Every device is connected to every other device. Very reliable because there are multiple paths, but expensive to set up.

```
PC1 --- PC2
 | \   / |
 |  \ /  |
 |  / \  |
PC4 --- PC3
```

### Hybrid Topology
A combination of two or more topologies, used in large organizations to balance cost, reliability, and performance.

---

## Client-Server vs Peer-to-Peer

| Feature | Client-Server | Peer-to-Peer (P2P) |
|---|---|---|
| Structure | Central server provides services | All devices are equal, no central server |
| Example | College website hosted on a server, accessed by students | File sharing directly between two laptops using a shared folder |
| Management | Easy to manage centrally | Harder to manage since no central control |
| Security | Easier to secure since server can be protected | Harder to secure since every device is a possible entry point |

**In simple words:** In client-server, one big computer (server) serves many smaller computers (clients) — like a canteen serving many students. In peer-to-peer, every computer can act as both a server and a client — like friends directly sharing notes with each other, without a canteen in between.

---

## Cyber Security Perspective

**Why is this important in Cyber Security?**

- **What can go wrong?** If a network is poorly designed or unprotected, attackers can enter through any weak point — a single unprotected device on a LAN can become an entry for the whole network.
- **What can an attacker do?** An attacker on the same network can intercept data, install malware, or move from one device to another (this is called "lateral movement").
- **What can be affected?** Confidential files, passwords, banking details, and even entire company operations can be compromised.
- **How can an organization protect itself?** By using firewalls, segmenting the network (separating sensitive parts), monitoring traffic, and applying strong access controls.

---

## Attack Example (Conceptual Only)

```
   Attacker
      |
      v
  Unsecured Wi-Fi (college lab)
      |
      v
  Student's Laptop (Vulnerability: no antivirus, weak password)
      |
      v
  Possible Impact: Data theft, malware spread to other devices on same network
```

This is explained only at a conceptual level to help you understand risk — not as instructions to attack anything.

---

## Defense / Protection

**Prevention**
- Use strong Wi-Fi passwords (WPA2/WPA3)
- Keep software and antivirus updated
- Separate sensitive devices onto different network segments

**Detection**
- Monitor network traffic for unusual activity
- Use tools like Wireshark to inspect suspicious packets

**Response**
- Disconnect the affected device from the network immediately
- Inform the network/IT administrator

**Recovery**
- Restore data from clean backups
- Change all passwords after an incident
- Patch the vulnerability that caused the issue

---

## Tools

| Tool | Used For | Beginner Explanation |
|---|---|---|
| Cisco Packet Tracer | Simulating networks | Lets you build and test a virtual network without real hardware |
| Wireshark | Capturing network traffic | Shows you what data is flowing through a network, packet by packet |

---

## Practical Example / Mini Lab

**Lab Objective:** Build a simple star topology network in Cisco Packet Tracer and connect 3 PCs through a switch.

**Requirements:** Cisco Packet Tracer software installed on your computer.

**Lab Topology:**
```
        [Switch]
       /   |   \
   PC1   PC2   PC3
```

**Steps:**
1. Open Cisco Packet Tracer
2. Drag one switch and three PCs onto the workspace
3. Use a copper straight-through cable to connect each PC to the switch
4. Assign IP addresses to each PC (e.g., 192.168.1.1, 192.168.1.2, 192.168.1.3)
5. Use the "ping" command from PC1 to PC2 to test connectivity

**Expected Result:** All PCs should successfully ping each other, confirming the network is working.

**What Did We Learn?**
- How devices are physically connected in a star topology
- How IP addresses allow devices to identify each other
- How to test connectivity using ping
- Why a central switch is important in this setup

---

## Important Terms

| Term | Simple Meaning |
|---|---|
| Network | Group of connected devices sharing data |
| Node | Any device connected to a network |
| Topology | The arrangement/layout of a network |
| LAN | Network within a small area like an office |
| WAN | Network spanning large distances like the internet |
| Bandwidth | The amount of data that can travel through a network at a time |
| Packet | A small unit of data sent over a network |

---

## Common Mistakes

**Mistake:** Thinking Wi-Fi and internet are the same thing.
**Why it is dangerous:** Wi-Fi is just a way to connect to a network locally; internet is a global network. Confusing the two can lead to poor troubleshooting.

**Mistake:** Using default router passwords.
**Why it is dangerous:** Default passwords are publicly known, making it easy for attackers to access your home or office network.

**Mistake:** Ignoring the difference between LAN and WAN when designing security.
**Why it is dangerous:** WAN traffic passes through public infrastructure and needs stronger protection like encryption, unlike a private LAN.

**Mistake:** Believing a mesh topology is always the best choice.
**Why it is dangerous:** While reliable, mesh is expensive and complex; not suitable for small setups like a home network.

**Mistake:** Not understanding client-server roles before working with real applications.
**Why it is dangerous:** Without this basic understanding, students struggle to grasp how websites, apps, and databases actually work together.

---

## Interview Questions

**Basic**

1. What is a computer network?
*A group of connected devices that share data and resources.*

2. What is the difference between LAN and WAN?
*LAN covers a small area like one building; WAN covers large areas like cities or countries.*

3. What is a client-server network?
*A network where a central server provides services to multiple client devices.*

**Intermediate**

1. Which topology is most fault-tolerant and why?
*Mesh topology, because it has multiple paths between devices, so if one link fails, data can still travel through another path.*

2. Why is network segmentation important in Cyber Security?
*It limits how far an attacker can move if they breach one part of the network, reducing overall damage.*

3. What is the main security risk in peer-to-peer networks?
*Since there is no central control, every device is a potential weak point, making it harder to secure and monitor.*

---

## Quick Revision

**Remember These Points**

1. A network connects devices to share data and resources
2. LAN = small area, WAN = large area, MAN = city-wide, PAN = personal devices
3. Star topology is the most commonly used due to easy management
4. Bus and Ring topologies have single points of failure
5. Mesh topology offers the best reliability but is costly
6. Client-Server is centralized; Peer-to-Peer is decentralized
7. Servers provide services; clients request them
8. Poorly secured networks are the most common entry point for attackers
9. Firewalls and network segmentation help prevent attacks
10. Wireshark and Packet Tracer are essential beginner tools for networking and security

---

## One-Minute Explanation

A computer network is a group of connected devices that share data with each other, just like friends sharing information through phone calls. Networks can be small like a college lab (LAN) or huge like the internet (WAN). Devices can be arranged in different layouts called topologies — Star, Bus, Ring, Mesh, or Hybrid — each with its own pros and cons. In a Client-Server setup, one central server serves many clients, while in Peer-to-Peer, all devices are equal. Understanding networks is the foundation of Cyber Security because almost every attack happens through a network. Tools like Wireshark and Cisco Packet Tracer help us practice and understand these concepts. Strong passwords, segmentation, and monitoring are basic ways to keep networks safe.
