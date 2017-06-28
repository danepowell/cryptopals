import unittest
import array
import crypt
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend

class CryptoTest(unittest.TestCase):

    def test_challenge_1(self):
        input_hex_str = '49276d206b696c6c696e6720796f757220627261696e206c696b65206120706f69736f6e6f7573206d757368726f6f6d'
        expected_b64_str = 'SSdtIGtpbGxpbmcgeW91ciBicmFpbiBsaWtlIGEgcG9pc29ub3VzIG11c2hyb29t\n'
        self.assertEqual(crypt.hex2b64(input_hex_str), expected_b64_str)

    def test_challenge_2(self):
        input_hex_str1 = '1c0111001f010100061a024b53535009181c'
        input_hex_str2 = '686974207468652062756c6c277320657965'
        expected_hex_str = '746865206b696420646f6e277420706c6179'
        out = crypt.fxor(input_hex_str1.decode('hex'), input_hex_str2.decode('hex'))
        self.assertEqual(out.encode('hex'), expected_hex_str)

    def test_challenge_3(self):
        input_hex_str = '1b37373331363f78151b7f2b783431333d78397828372d363c78373e783a393b3736'
        input_bin_str = input_hex_str.decode('hex')
        expected_key = 'X'
        result = crypt.guess_1byte_xor(input_bin_str)
        self.assertEqual(result['key'], expected_key)

    def test_challenge_4(self):
        cipher_lines = [line.rstrip('\n') for line in open('../resources/4.txt')]
        plain_lines = {}
        for cipher_line in cipher_lines:
            plain_line = crypt.guess_1byte_xor(cipher_line.decode('hex'))
            plain_lines[plain_line['score']] = {'key': plain_line['key'], 'text': plain_line['text']}
        best_line = plain_lines[max(plain_lines)]
        self.assertEqual(best_line['key'], '5')

    def test_challenge_5(self):
        plain_text = "Burning 'em, if you ain't quick and nimble\nI go crazy when I hear a cymbal"
        key = "ICE"
        cipher = crypt.rxor(plain_text, key)
        self.assertEqual('0b3637272a2b2e63622c2e69692a23693a2a3c6324202d623d63343c2a26226324272765272a282b2f20430a652e2c652a3124333a653e2b2027630c692b20283165286326302e27282f', cipher.encode('hex'))

    def test_challenge_6(self):
        str1 = 'this is a test'
        str2 = 'wokka wokka!!!'
        distance = crypt.hamming(str1, str2)
        self.assertEqual(distance, 37)

        cipher_lines = [line.rstrip('\n') for line in open('../resources/6.txt')]
        cipher_str = ''.join(cipher_lines)
        cipher_str = cipher_str.decode('base64')
        keysize = crypt.rxor_keysize(cipher_str)
        self.assertEqual(keysize, 29)

        key = crypt.rxor_crack(cipher_str, keysize)
        self.assertEqual(key, 'Terminator X: Bring the noise')

    def test_challenge_7(self):
        cipher_lines = [line.rstrip('\n') for line in open('../resources/7.txt')]
        cipher_str = ''.join(cipher_lines)
        cipher_str = cipher_str.decode('base64')

        backend = default_backend()
        cipher = Cipher(algorithms.AES("YELLOW SUBMARINE"), modes.ECB(), backend=backend)
        decryptor = cipher.decryptor()
        plain_str = decryptor.update(cipher_str) + decryptor.finalize()
        self.assertEqual(plain_str[:8], "I'm back")

    def test_challenge_8(self):
        cipher_lines = [line.rstrip('\n').decode('hex') for line in open('../resources/8.txt')]
        dupes = 0;
        for line in cipher_lines:
            blocks = [line[x:x + 16] for x in range(0, len(line), 16)]
            if len(blocks) != len(set(blocks)):
                dupes += 1
        self.assertEqual(dupes, 1);

if __name__ == '__main__':
    unittest.main()
