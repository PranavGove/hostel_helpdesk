#!/usr/bin/env python3
import sys

def diagnose(complaint):
    """Rule-based expert system for hostel complaints"""
    if not complaint:
        return "Others|General Issue|Please describe your problem clearly"
    
    complaint = complaint.lower().strip()
    
    # Electrical problems
    if any(word in complaint for word in ["light", "bulb", "fan", "switch", "power"]):
        if "fan" in complaint:
            return "Electrical|Fan Issue|1. Check power connection. 2. Verify circuit breaker."
        elif "light" in complaint:
            return "Electrical|Lighting Issue|1. Replace bulb. 2. Check switches."
    
    # Plumbing problems
    elif any(word in complaint for word in ["leak", "tap", "water", "pipe", "toilet", "basin"]):
        if "leak" in complaint:
            return "Plumbing|Water Leak|1. Turn off main valve if possible. 2. Call warden on 7645782345."
        elif "toilet" in complaint:
            return "Plumbing|Toilet Issue|1. Use plunger. 2. Don't flush if clogged. 3. report at 8765465743."
    
    # IT problems
    elif any(word in complaint for word in ["wifi", "internet", "network", "ethernet"]):
        return ("IT|Connectivity Issue|"
                "1. Check cable connection - ensure Ethernet cable is properly connected to PC and router. "
                "2. Restart the system - reboot PC and router to refresh the connection. "
                "3. Enable Ethernet adapter - check network settings and enable Ethernet interface. "
                "4. Check IP address - use 'ipconfig' or 'ifconfig' to verify IP is assigned. "
                "5. Renew IP address - run 'ipconfig /release' and 'ipconfig /renew' (Windows). "
                "6. Use network troubleshooter - run built-in troubleshooter to detect issues.")
    
    # Furniture problems
    elif any(word in complaint for word in ["bed", "chair", "table", "furniture"]):
        return "Furniture|Damage Report|1. Stop using damaged item. 2. Submit repair request. 3. Wait for technician."
    
    # Cleaning requests
    elif any(word in complaint for word in ["clean", "dirty", "trash", "mop"]):
        return "Housekeeping|Cleaning Request|Your cleaning request has been logged. Staff will come within 2 hours."
    
    # Default response
    return "Others|General Issue|We've registered your complaint. Our staff will contact you shortly."

if __name__ == "__main__":
    try:
        user_complaint = sys.argv[1] if len(sys.argv) > 1 else ""
        result = diagnose(user_complaint)
        print(result)
    except Exception as e:
        print("Others|System Error|Please visit the help desk in person")
