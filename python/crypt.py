import array

def fxor(str1, str2):
    arr1 = array.array('B', str1)
    arr2 = array.array('B', str2)
    arr3 = array.array('B', '')
    for i in range(len(arr1)):
        arr3.append(arr1[i] ^ arr2[i])
    return arr3.tostring()

def score(str1):
    str2 = str1.lower()
    if (all(ord(c) < 128 for c in str1)):
        return str2.count("e")
    else:
        return 0

def hex2b64(hex_str):
    return hex_str.decode('hex').encode('base64')
